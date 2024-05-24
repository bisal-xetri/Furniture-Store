<?php include('config/constant.php') ?>
<?php include('partial-front/menu.php') ?>


<?php

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
// use PHPMailer\PHPMailer\Exception;
//Load Composer's autoloader
require 'vendor/autoload.php';
//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);
if (isset($_SESSION['id'])) {
    $customer_id    = $_SESSION['id'];
    $customer_email = $_SESSION['email'];
    $customer_name  = $_SESSION['username'];
    $customer_add   = $_SESSION['add'];
    $customer_number = $_SESSION['number'];

    $sub_total = 0;
    $shipping_cost = 0;
    $total = 0;

    if (isset($_POST['checkout']) || isset($_POST['esewa'])) {
        $fullname = $_POST['fullname'];
        $address  = $_POST['address'];
        $number   = $_POST['phone_number'];

        date_default_timezone_set('UTC');

        // Get current UTC time
        $utc_time = time();

        // Add 5 hours and 45 minutes to convert to Nepal Standard Time
        $nepal_time = $utc_time + (5 * 3600) + (45 * 60);

        // Format the Nepal Standard Time
        $order_date = date("Y-m-d H:i:s", $nepal_time);
        $status = "Ordered";
        $esewa = isset($_POST['esewa']) ? 1 : 0;

        // After sending, remove unnecessary debug info
        // header("location:" . SITEURL . 'customer/orders.php');
        $cartt = "SELECT * FROM cart WHERE user_id='$customer_id'";
        $run  = mysqli_query($con, $cartt);
        if (mysqli_num_rows($run) > 0) {
            $table = '<table border="1">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                </tr>
            </thead>
            <tbody>';

            while ($row = mysqli_fetch_array($run)) {
                $db_pro_id  = $row['product_id'];
                $db_pro_qty  = $row['quantity'];


                $pr_query  = "SELECT * FROM tbl_furniture WHERE id=$db_pro_id";
                $pr_run    = mysqli_query($con, $pr_query);
                if (mysqli_num_rows($pr_run) > 0) {
                    while ($pr_row = mysqli_fetch_array($pr_run)) {
                        $price = $pr_row['price'];
                        $product_name = $pr_row['title'];

                        $product_id = $pr_row['id']; // Fetching product_id from the database

                        $single_pro_total_price = $db_pro_qty * $price;
                        $table .= '<tr>
                        <td>' . $product_name . '</td>
                        <td>' . $db_pro_qty . '</td>
                        <td>'.'Rs.' . $single_pro_total_price . '</td>
                    </tr>';
                        $table .= '</tbody></table>';

                        $checkout_query = "INSERT INTO tbl_order SET
                          furniture='$product_name',
                          price=$price,
                          qty=$db_pro_qty,
                          total=$single_pro_total_price,
                          order_date='$order_date',
                          status='$status',
                          customer_name='$customer_name',
                          customer_contact='$number',
                          customer_email='$customer_email',
                          customer_address='$address',
                          customer_id=$customer_id,
                          product_id=$db_pro_id,
                            esewa=$esewa
                          ";
                      
                        if (mysqli_query($con, $checkout_query)) {
                              //Server settings
                        // $mail->SMTPDebug = SMTP::DEBUG_SERVER; //Enable verbose debug output
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
                        $mail->Body = 'Thanks! for your order. It will be delivered within 7 working days.<br><br>' . $table;
                        $mail->isHTML(true);
                        $mail->send();
                        $mail->SMTPDebug = 0;
                            $del_query = "DELETE FROM cart WHERE user_id = $customer_id AND product_id = $db_pro_id";
                            if (mysqli_query($con, $del_query)) {
                                $_SESSION['message'] = "<div style='color:green; text-align:center'>
                                    Thanks! for your order, It will be delivered within 7 working days.
                                </div>";
                                header("location:" . SITEURL . 'customer/orders.php');
                            }
                        }
                    }
                }
            }
        }
    }
?>

    <h1 class="checkout-title">Check Out</h1>
    <div class="checkout-main">

        <div class="checkout-form">
            <h2>Shipping Details</h2>
            <hr>
            <form action="" method="post">
                <label for="email"><b>Email:</b></label>
                <label><b><?php echo $customer_email; ?></b></label><br><br>
                <label for="fullname">FullName:</label>
                <input type="text" name="fullname" placeholder="Full Name" class="form-control" value="<?php echo $customer_name; ?>" required><br><br>
                <label for="address">Address:</label>
                <input type="text" name="address" placeholder="Address" value="<?php echo $customer_add; ?>" class="form-control"><br><br>
                <label for="number">Number:</label>
                <input type="number" name="phone_number" placeholder="Phone Number" class="form-control" value="<?php echo $customer_number; ?>" required><br><br>

                <input type="submit" name="checkout" class="checkout-btn" value="Cash On Delivery" id="border-less">

            </form>
            <?php
            $cart = "SELECT * FROM cart WHERE user_id='$customer_id'";
            $run  = mysqli_query($con, $cart);
            if (mysqli_num_rows($run) > 0) {
                while ($cart_row = mysqli_fetch_array($run)) {
                    $db_cust_id = $cart_row['user_id'];
                    $db_pro_id  = $cart_row['product_id'];
                    $db_pro_qty  = $cart_row['quantity'];

                    $pr_query  = "SELECT * FROM tbl_furniture WHERE id=$db_pro_id";
                    $pr_run    = mysqli_query($con, $pr_query);

                    if (mysqli_num_rows($pr_run) > 0) {
                        while ($pr_row = mysqli_fetch_array($pr_run)) {
                            $pid = $pr_row['id'];
                            $title = $pr_row['title'];
                            $price = $pr_row['price'];
                            $arrPrice = array($pr_row['price']);

                            $img1 = $pr_row['image_name'];

                            $single_pro_total_price = $db_pro_qty * $price;
                            $pro_total_price = array($db_pro_qty * $price);
                            $each_pr = implode($pro_total_price);
                            //   $values = array_sum($arrPrice);
                            $shipping_cost = 0;
                            $values = array_sum($pro_total_price);
                            $sub_total += $values;
                            $total = $sub_total + $shipping_cost;
                        }
                    }
                }
            }
            // Secret key provided by eSewa
            $tuid = time();
            $t_amt = $total;

            $message = "total_amount=$t_amt,transaction_uuid=$tuid,product_code=EPAYTEST";
            $s = hash_hmac('sha256', $message, '8gBm/:&EnhH.1/q', true);
            $signature = base64_encode($s);
            //echo $signature
            ?>

            <form action="https://rc-epay.esewa.com.np/api/epay/main/v2/form" method="post">
                <!-- Required hidden fields -->
                <input type="hidden" id="amount" name="amount" value="<?php echo $total; ?>" required>
                <input type="hidden" id="tax_amount" name="tax_amount" value="0" required>
                <input type="hidden" id="total_amount" name="total_amount" value="<?php echo $total; ?>" required>
                <input type="hidden" id="transaction_uuid" name="transaction_uuid" value="<?php echo $tuid; ?>" required>
                <input type="hidden" id="product_code" name="product_code" value="EPAYTEST" required>
                <input type="hidden" id="product_service_charge" name="product_service_charge" value="0" required>
                <input type="hidden" id="product_delivery_charge" name="product_delivery_charge" value="0" required>
                <input type="hidden" id="success_url" name="success_url" value="http://localhost/FurnitureStore/esewa_payment_success.php" required>
                <input type="hidden" id="failure_url" name="failure_url" value="https://rc-epay.esewa.com.np/api/epay/main/v2/form" required>
                <input type="hidden" id="signed_field_names" name="signed_field_names" value="total_amount,transaction_uuid,product_code" required>
                <input type="hidden" id="signature" name="signature" value="<?php echo $signature; ?>" required>

                <!-- Submit button -->
                <input type="submit" name="esewa" class="epay checkout-btn" value="Pay with eSewa">
            </form>

        </div>

        <div class="checkout-cart">
            <h2>Order Detail</h2>
            <hr>

            <table class="checkout-tbl">
        <thead>
            <tr>
                <th>Product Image</th>
                <th>Name</th>
                <th>Quantity</th>
                <th>Total Price</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $cart = "SELECT * FROM cart WHERE user_id='$customer_id'";
            $run  = mysqli_query($con, $cart);
            if (mysqli_num_rows($run) > 0) {
                while ($cart_row = mysqli_fetch_array($run)) {
                    $db_cust_id = $cart_row['user_id'];
                    $db_pro_id  = $cart_row['product_id'];
                    $db_pro_qty  = $cart_row['quantity'];

                    $pr_query  = "SELECT * FROM tbl_furniture WHERE id=$db_pro_id";
                    $pr_run    = mysqli_query($con, $pr_query);
                    if (mysqli_num_rows($pr_run) > 0) {
                        while ($pr_row = mysqli_fetch_array($pr_run)) {
                            $pid = $pr_row['id'];
                            $title = $pr_row['title'];
                            $price = $pr_row['price'];
                            $img1 = $pr_row['image_name'];

                            $single_pro_total_price = $db_pro_qty * $price;
                            echo '<tr>
                                <td><img class="checkout-img" src="Image/furniture/' . $img1 . '" alt=""></td>
                                <td>' . $title . '</td>
                                <td>x ' . $db_pro_qty . '</td>
                                <td>Rs.' . $single_pro_total_price . '</td>
                            </tr>';
                        }
                    }
                }
            }
            ?>
        </tbody>
    </table>
            <div class="checkout-total">
                <div class="checkout-total-info">
                    <p>Subtotal</p>
                    <p>Shipping</p>
                    <hr>
                    <h3>TOTAL</h3>
                </div>
                <div class="checkout-total-calculate">
                    <p>Rs.<?php echo $sub_total; ?></p>
                    <p>Rs.<?php echo $shipping_cost; ?></p>
                    <hr>
                    <h3>Rs.<?php echo $total; ?></h3>
                </div>
            </div>
        </div>
    <?php
}
    ?>
    </div>

    <?php include('partial-front/footer.php') ?>