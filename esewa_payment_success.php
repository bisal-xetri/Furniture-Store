<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
// use PHPMailer\PHPMailer\Exception;
//Load Composer's autoloader
require 'vendor/autoload.php';
//Create an instance; passing `true` enables exceptions

// Function to handle response from eSewa after successful payment
function handleEsewaResponse($data, $con)
{
    // Decode the base64 encoded response data
    $decodedData = base64_decode($data);

    // Convert JSON string to associative array
    $response = json_decode($decodedData, true);
    
    // Extract response parameters
    $status = $response['status'];
    $signature = $response['signature'];
    $transactionCode = $response['transaction_code'];
    $totalAmount = $response['total_amount'];
    $transactionUuid = $response['transaction_uuid'];
    $productCode = $response['product_code'];

    // Verify the integrity of the response by generating a signature and comparing it with the received signature
    $secretKey = '8gBm/:&EnhH.1/q'; // Replace 'your_secret_key' with your actual eSewa secret key

    // Concatenate the message parameters
    $message = "total_amount=$totalAmount,transaction_uuid=$transactionUuid,transaction_code=$transactionCode";
    //$message = "total_amount=$t_amt,transaction_uuid=$tuid,product_code=EPAYTEST";
    // Generate the HMAC-SHA256 hash using the secret key
    $generatedSignature = base64_encode(hash_hmac('sha256', $message, $secretKey, true));
    echo $generatedSignature."</br>";
    echo $signature."</br>";
    echo $transactionCode."</br>";
    echo $status."</br>";

    // if ($generatedSignature === $signature) {
        // Signature matches, proceed with processing the transaction
        if ($status === "COMPLETE") {
            
            // Transaction is successful, insert data into the database and update the esewa column value to 1
            // Assuming you have already fetched necessary data from the session or elsewhere
            $customer_id = $_SESSION['id'];
            $customer_email = $_SESSION['email'];
            $customer_name = $_SESSION['username'];
            $customer_add = $_SESSION['add'];
            $customer_number = $_SESSION['number'];

            // Fetch cart items for the user
            $cartQuery = "SELECT * FROM cart WHERE user_id='$customer_id'";
            $cartResult = mysqli_query($con, $cartQuery);
            $table = '<table border="1">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                </tr>
            </thead>
            <tbody>';

            if ($cartResult) {
                while ($row = mysqli_fetch_array($cartResult)) {
                    $db_pro_id = $row['product_id'];
                    $db_pro_qty = $row['quantity'];

                    // Fetch product details
                    $productQuery = "SELECT * FROM tbl_furniture WHERE id=$db_pro_id";
                    $productResult = mysqli_query($con, $productQuery);

                    if ($productResult && mysqli_num_rows($productResult) > 0) {
                        $productData = mysqli_fetch_assoc($productResult);
                        $product_name = $productData['title'];
                        $price = $productData['price'];
                        $single_pro_total_price = $db_pro_qty * $price;
                        $table .= '<tr>
                        <td>' . $product_name . '</td>
                        <td>' . $db_pro_qty . '</td>
                        <td>'.'Rs.' . $single_pro_total_price . '</td>
                    </tr>';
                        $table .= '</tbody></table>';
                        // Insert order details into tbl_order
                        $insertOrderQuery = "INSERT INTO tbl_order (furniture, price, qty, total, order_date, status, customer_name, customer_contact, customer_email, customer_address, customer_id, esewa, product_id)
                                            VALUES ('$product_name', $price, $db_pro_qty, $single_pro_total_price, NOW(), 'Ordered', '$customer_name', '$customer_number', '$customer_email', '$customer_add', $customer_id, 1, $db_pro_id)";

                        $insertOrderResult = mysqli_query($con, $insertOrderQuery);
                        $mail = new PHPMailer(true);
                        $mail->isSMTP(); //Send using SMTP
                        $mail->Host       = 'smtp.gmail.com'; //Set the SMTP server to send through
                        $mail->SMTPAuth   = true; //Enable SMTP authentication
                        $mail->Username   = 'dhakalbishal42@gmail.com'; //SMTP username
                        $mail->Password   = 'irxsxafgpbegccxg'; //SMTP password
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
                        $mail->Port       = 465; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                        //Recipients
                        $mail->setFrom('dhakalbishal42@gmail.com', 'Himalayan Furniture');
                        $mail->addAddress($customer_email, $customer_name); //Add a recipient
                        $mail->Subject = 'Orders Confirmation Message';
                        $mail->Body = 'Thanks! for your order. It will be delivered within 7 working days.<br><br>'.$table;
                        $mail->isHTML(true);
                        $mail->send();
                        $mail->SMTPDebug = 0;

                        if ($insertOrderResult) {
                            // Order inserted successfully, remove item from cart
                            $deleteCartQuery = "DELETE FROM cart WHERE user_id = $customer_id AND product_id = $db_pro_id";
                            $deleteCartResult = mysqli_query($con, $deleteCartQuery);

                            if (!$deleteCartResult) {
                                echo "Error: Failed to remove item from cart.";
                            }
                        } else {
                            echo "Error: Failed to insert order details.";
                        }
                    } else {
                        echo "Error: Product not found.";
                    }
                }
            } else {
                echo "Error: Failed to fetch cart items.";
            }

            // Redirect to the success page
            header("location:" . SITEURL . 'customer/orders.php');
            exit();
        } else {
            // Handle other transaction statuses like PENDING, FULL_REFUND, CANCELED, etc.
        }
     } //else {
//         // Signature doesn't match, do not process the transaction and log an error
//         // You can log the error or display an error message to the user
//         echo "Error: Signature mismatch. Transaction may be tampered with.";
//     }
// }

// Call the function with the data received from eSewa
if (isset($_GET['data'])) {
    // Include your database connection file and establish a connection
    include('config/constant.php'); // Adjust the path if necessary
    // Replace 'your_secret_key' with your actual eSewa secret key provided by eSewa
    handleEsewaResponse($_GET['data'], $con);
} else {
    // Handle the case where data is not provided in the URL
    echo "Error: Data not found.";
}
