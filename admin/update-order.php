<?php include('partials/menu.php') ?>
<div class="main-content">
    <div class="wrapper">
        <h1>Update Order</h1>
        <br>
        <?php
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $sql = "SELECT * FROM tbl_order WHERE id = $id";
            $res = mysqli_query($con, $sql);
            $count = mysqli_num_rows($res);
            if ($count == 1) {

                $row = mysqli_fetch_assoc($res);
                $id = $row['id'];

                $furniture = $row['furniture'];
                $price = $row['price'];
                $qty = $row['qty'];
                $total = $row['total'];
                $status = $row['status'];
                $customer_name = $row['customer_name'];
                $customer_email = $row['customer_email'];
                $customer_contact = $row['customer_contact'];
                $customer_address = $row['customer_address'];
            } else {
                header("Location:" . SITEURL . "admin/manage-order.php");
            }
        } else {
            header("Location:" . SITEURL . "admin/manage-order.php");
        }
        ?>
        <br>
        <form action="" method="post">
            <table class="tbl-30">
                <tr>
                    <td>Furniture Name:</td>
                    <td style='font-weight:bold;'><?php echo $furniture; ?></td>
                </tr>
                <tr>
                    <td>Price:</td>
                    <td style='font-weight:bold;'>Rs.<?php echo $price; ?></td>
                </tr>
                <tr>
                    <td>Qty:</td>
                    <td><input class='update-input' type="number" name='qty' value="<?php echo $qty; ?>"></td>
                </tr>
                <tr>
                    <td>Status:</td>
                    <td>
                        <select name="status" class='update-input' id="">
                            <option <?php if ($status == "Ordered") {
                                        echo "selected";
                                    } ?> value="Ordered">Ordered</option>
                            <option <?php if ($status == "On Delivery") {
                                        echo "selected";
                                    } ?> value="On Delivery">On Delivery</option>
                            <option <?php if ($status == "Delivered") {
                                        echo "selected";
                                    } ?> value="Delivered">Delivered</option>
                            <option <?php if ($status == "Canceled") {
                                        echo "selected";
                                    } ?> value="Canceled">Canceled</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Customer Name:</td>
                    <td><input class='update-input' type="text" name='customer_name' value="<?php echo $customer_name; ?>"></td>
                </tr>
                <tr>
                    <td>CustomerContact:</td>
                    <td><input class='update-input' type="number" name='customer_contact' value="<?php echo $customer_contact; ?>"></td>
                </tr>
                <tr>
                    <td>Customer Email:</td>
                    <td><input class='update-input' type="email" name='customer_email' value="<?php echo $customer_email; ?>"></td>
                </tr>
                <tr>
                    <td>Customer Address:</td>
                    <td><textarea class='update-input' name="customer_address" id="" cols="17" rows="5"> <?php echo $customer_address; ?></textarea></td>
                </tr>
                <tr>
                    <td colspan='2'>
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="hidden" name="price" value="<?php echo $price; ?>">
                        <input type="submit" name='submit' value="Update Order" class='btn-secondary'>
                    </td>
                </tr>
            </table>

        </form>
        <?php
        if (isset($_POST['submit'])) {
            $id = $_POST['id'];
            $price = $_POST['price'];
            $qty = $_POST['qty'];
            $total = $price * $qty;
            $status = $_POST['status'];
            $customer_name = $_POST['customer_name'];
            $customer_email = $_POST['customer_email'];
            $customer_address = $_POST['customer_address'];
            $customer_contact = $_POST['customer_contact'];
            $sql2 = "UPDATE tbl_order SET
            qty=$qty,
            total=$total,
            status='$status',
            customer_name='$customer_name',
            customer_email='$customer_email',
            customer_address='$customer_address',
            customer_contact='$customer_contact'
            WHERE id = $id";
            $res2 = mysqli_query($con, $sql2);
            if ($res2) {
                $_SESSION['update'] = "<div class='success'>Ordered Updated Successfully!</div>";
                header("Location:" . SITEURL . "admin/manage-order.php");
            } else {
                $_SESSION['update'] = "<div class='error'>Failed to Update!</div>";
                header("Location:" . SITEURL . "admin/manage-order.php");
            }
        }

        ?>
    </div>
</div>
<?php include('partials/footer.php') ?>