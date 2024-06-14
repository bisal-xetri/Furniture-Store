<?php ob_start(); ?>
<?php include('config/constant.php') ?>
<?php include('partial-front/menu.php') ?>

<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require 'vendor/autoload.php';

if (isset($_SESSION['username'])) {
    if (isset($_SESSION['id'])) {
        $custid = $_SESSION['id'];

        if (isset($_GET['furniture_id'])) {
            $furniture_id = $_GET['furniture_id'];
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
                echo "<script>alert('⚠️ Login is required to buy this product');</script>";
                header("Location:" . SITEURL);
            }
        }
    }
} else {
    echo "<script>alert('⚠️ Login is required to buy this product');</script>";
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

        <input type="submit" name="submit" value="Cash On Delivery" class="btn btn-primary" />
        <input type="submit" name="esewa" value="Pay with Esewa" class="btn epay" />
    </form>

    <?php
    if (isset($_POST['submit']) || isset($_POST['esewa'])) {
        // Get all the details of the order
        $furniture = $_POST['furniture'];
        $price = $_POST['price'];
        $qty = $_POST['qty'];
        $total = $price * $qty;
        date_default_timezone_set('UTC');
        $utc_time = time();
        $nepal_time = $utc_time + (5 * 3600) + (45 * 60);
        $order_date = date("Y-m-d H:i:s", $nepal_time);
        $status = "Ordered";
        $customer_name = $_POST['full-name'];
        $customer_contact = $_POST['contact'];
        $customer_email = $_POST['email'];
        $customer_address = $_POST['address'];
        $esewa = isset($_POST['esewa']) ? 1 : 0;

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

            if (isset($_POST['esewa'])) {
                // Redirect to eSewa payment gateway
                $tuid = time();
                $t_amt = $total;
    
                $message = "total_amount=$t_amt,transaction_uuid=$tuid,product_code=EPAYTEST";
                $s = hash_hmac('sha256', $message, '8gBm/:&EnhH.1/q', true);
                $signature = base64_encode($s);
                $success_url = SITEURL . "customer/orders.php";
                $failure_url = SITEURL . "esewa_payment_failure.php";
        
                echo "
                <form id='esewaForm' action='https://rc-epay.esewa.com.np/api/epay/main/v2/form' method='POST'>
                    <input type='hidden' id='amount' name='amount' value='$total' required>
                    <input type='hidden' id='tax_amount' name='tax_amount' value='0' required>
                    <input type='hidden' id='total_amount' name='total_amount' value='$total' required>
                    <input type='hidden' id='transaction_uuid' name='transaction_uuid' value='$tuid' required>
                    <input type='hidden' id='product_code' name='product_code' value='EPAYTEST' required>
                    <input type='hidden' id='product_service_charge' name='product_service_charge' value='0' required>
                    <input type='hidden' id='product_delivery_charge' name='product_delivery_charge' value='0' required>
                    <input type='hidden' id='success_url' name='success_url' value='$success_url' required>
                    <input type='hidden' id='failure_url' name='failure_url' value='$failure_url' required>
                    <input type='hidden' id='signed_field_names' name='signed_field_names' value='total_amount,transaction_uuid,product_code' required>
                    <input type='hidden' id='signature' name='signature' value='$signature' required>
                </form>
                <script type='text/javascript'>
                    document.getElementById('esewaForm').submit();
                </script>
                ";
            } else {
                // Redirect to orders page
                $_SESSION['order'] = "<div class='success'>Order placed successfully.</div>";
                header("Location:" . SITEURL . "orders.php");
            }
        } else {
            $_SESSION['order'] = "<div class='error'>Failed to place order.</div>";
            header("Location:" . SITEURL);
        }
    }
    ?>
</div>

<?php include('partial-front/footer.php'); ?>
