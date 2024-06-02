<?php ob_start(); ?>
<?php include('config/constant.php') ?>
<?php include('partial-front/menu.php') ?>
<?php
// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

// Load Composer's autoloader
require 'vendor/autoload.php';

if (isset($_SESSION['username'])) {
    // Check if 'id' session variable is set
    if (isset($_SESSION['id'])) {
        $custid = $_SESSION['id'];

        if (isset($_GET['furniture_id'])) {
            //get the furniture id
            $furniture_id = $_GET['furniture_id'];
            //get details of the selected furniture
            $sql = "SELECT * FROM tbl_furniture WHERE id = $furniture_id";
            $res = mysqli_query($con, $sql);
            $count = mysqli_num_rows($res);
            if ($count == 1) {
                $row = mysqli_fetch_assoc($res);
                $id = $row['id'];
                $title = $row['title'];
                $price = $row['price'];
                $image_name = $row['image_name'];
            } else {
                echo "<script> function a(){alert('⚠️ Login is required to buy this product');}</script>";
                header("Location:" . SITEURL);
            }
        }
    }
} else {
    //redirect to home
    echo "<script> function a(){alert('⚠️ Login is required to buy this product');}</script>";
    header('Location:' . SITEURL);
}
?>
<link rel="stylesheet" href="CSS/buy.css">

<div class="main">
    <form action="" method="post" class="order">
        <fieldset class="user-details">
            <legend>Selected Furniture</legend>
            <div class="selected-furniture">
                <div class="selected-furniture-img">
                    <?php
                    if ($image_name == "") {
                        //img not available
                        echo "<div class='error'>Image not available</div>";
                    } else {
                    ?>
                        <img src="<?php echo SITEURL; ?>/Image/furniture/<?php echo $image_name; ?>" alt="" class="selected-image" />
                    <?php
                    }
                    ?>
                </div>
                <div class="selected-furniture-des">
                    <h3><?php echo $title; ?></h3>
                    <input type="hidden" name="furniture" value="<?php echo $title; ?>">
                    <p class="selected-furniture-price">Rs.<?php echo $price ?></p>
                    <input type="hidden" name="price" value="<?php echo $price ?>">

                    <div class="order-label">Quantity</div>
                    <input type="number" name="qty" class="input-responsive" min="1" value="1" required />
                </div>
            </div>
        </fieldset>
        <fieldset class="user-details">
            <?php
            $sql3 = "SELECT * FROM tbl_user WHERE id = $custid";
            $res3 = mysqli_query($con, $sql3);
            $count3 = mysqli_num_rows($res3);
            if ($count3 == 1) {
                $row3 = mysqli_fetch_array($res3);
                $name = $row3['full_name'];
                $phone = $row3['phone'];
                $email = $row3['email'];
                $address = $row3['address'];
            }
            ?>
            <legend>Delivery Details</legend>
            <div class="order-label">Full Name</div>
            <input type="text" name="full-name" value="<?php echo $name; ?>" placeholder="E.g. Bishal Dhakal" class="input-responsive" required />

            <div class="order-label">Phone Number</div>
            <input type="tel" name="contact" value="<?php echo $phone; ?>" placeholder="E.g. 9843xxxxxx" class="input-responsive" required />

            <div class="order-label">Email</div>
            <input type="email" name="email" value="<?php echo $email; ?>" placeholder="E.g. bishal@gmail.com" class="input-responsive" required />

            <div class="order-label">Address</div>
            <textarea name="address" rows="5" placeholder="E.g. Street, City, Country" class="input-responsive" required><?php echo $address; ?></textarea>
        </fieldset>

        <input type="submit" name="submit" value="Confirm Order" class="btn btn-primary" />
    </form>

    <?php
    if (isset($_POST['submit'])) {
        //get all the details of the order
        $furniture = $_POST['furniture'];
        $price = $_POST['price'];
        $qty = $_POST['qty'];
        $total = $price * $qty; //
        date_default_timezone_set('UTC');

        // Get current UTC time
        $utc_time = time();
        
        // Add 5 hours and 45 minutes to convert to Nepal Standard Time
        $nepal_time = $utc_time + (5 * 3600) + (45 * 60);
        
        // Format the Nepal Standard Time
        $order_date = date("Y-m-d H:i:s", $nepal_time);
        $status = "Ordered"; //
        $customer_name = $_POST['full-name'];
        $customer_contact = $_POST['contact'];
        $customer_email = $_POST['email'];
        $customer_address = $_POST['address'];
        $esewa = 0;  // eSewa value set to 0

        $sql2 = "INSERT INTO tbl_order SET
        furniture='$furniture',
        price=$price,
        qty=$qty,
        total=$total,
        order_date='$order_date',
        status='$status',
        customer_name='$customer_name',
        customer_contact='$customer_contact',
        customer_email='$customer_email',
        customer_address='$customer_address',
        customer_id=$custid,
        product_id=$id,
        esewa=$esewa
        ";
        $res2 = mysqli_query($con, $sql2);
        if ($res2) {
            // Send confirmation email
            $mail = new PHPMailer(true);
            try {
                //Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'dhakalbishal42@gmail.com';
                $mail->Password = 'irxsxafgpbegccxg';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port = 465;

                //Recipients
                $mail->setFrom('dhakalbishal42@gmail.com', 'Himalayan Furniture');
                $mail->addAddress($customer_email, $customer_name);

                //Content
                $mail->isHTML(true);
                $mail->Subject = 'Order Confirmation';
                $mail->Body = 'Thank you for your order. Your order details are as follows:<br>' .
                    'Furniture: ' . $furniture . '<br>' .
                    'Price: Rs.' . $price . '<br>' .
                    'Quantity: ' . $qty . '<br>' .
                    'Total: Rs.' . $total . '<br>' .
                    'Order Date: ' . $order_date . '<br>';

                $mail->send();
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }

            // Redirect to orders page
            $_SESSION['order'] = "<div class='success text-center' style='text-align:center;color:green;'>Furniture order Success.</div>";
            header("location:" . SITEURL . 'customer/orders.php');
        } else {
            $_SESSION['order'] = "<div class='error' style='text-align:center;color:red;'>Failed to order Furniture. " . mysqli_error($con) . "</div>";
            header("location:" . SITEURL);
        }
    }
    ?>
</div>

<?php include('partial-front/footer.php') ?>
<?php ob_end_flush(); ?>
