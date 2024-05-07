<?php include('partials/menu.php') ?>
<div class="main-content">
    <div class="wrapper">
        <h1>Manage Order</h1>
        <br>
        <?php
        if (isset($_SESSION['update'])) {
            echo $_SESSION['update'];
            unset($_SESSION['update']);
        }
        ?>
        <br>
        <table class="tbl-full">
            <tr>
                <th>S.N.</th>
                <th>Furniture</th>
                <th>Price</th>
                <th>Oty.</th>
                <th>Total</th>
                <th>Order Date</th>
                <th>Status</th>
                <th>Customer</th>
                <th> Contact</th>
                <th>Email</th>
                <th>Address</th>
                <th>E-sewa</th>
                <th>Actions</th>
            </tr>
            <?php
            $sql = "SELECT * FROM tbl_order ORDER BY id DESC";
            $res = mysqli_query($con, $sql);
            $count = mysqli_num_rows($res);
            $sn = 1;
            if ($count > 0) {
                while ($row = mysqli_fetch_assoc($res)) {
                    $id = $row['id'];
                    $furniture = $row['furniture'];
                    $price = $row['price'];
                    $qty = $row['qty'];
                    $total = $row['total'];
                    $order_date = $row['order_date'];
                    $status = $row['status'];
                    $customer_name = $row['customer_name'];
                    $customer_contact = $row['customer_contact'];
                    $customer_email = $row['customer_email'];
                    $customer_address = $row['customer_address'];
                    $esewa=$row['esewa'];
            ?>
                    <tr class="font-size">
                        <td><?php echo $sn++; ?></td>
                        <td><?php echo $furniture; ?></td>
                        <td><?php echo $price; ?></td>
                        <td><?php echo $qty; ?></td>
                        <td><?php echo $total; ?></td>
                        <td><?php echo $order_date; ?></td>
                        <td>
                            <?php
                            if ($status == 'Ordered') {
                                echo "<label style='color:#f7b731;'>$status</label>";
                            } else if ($status == 'On Delivery') {
                                echo "<label style='color:#f9ca24;'>$status</label>";
                            } else if ($status == 'Delivered') {
                                echo "<label style='color:#26de81;'>$status</label>";
                            } else if ($status == 'Canceled') {
                                echo "<label style='color:#eb4d4b;'>$status</label>";
                            }
                            ?>

                        </td>
                        <td><?php echo $customer_name; ?></td>
                        <td><?php echo $customer_contact; ?></td>
                        <td><?php echo $customer_email; ?></td>
                        <td><?php echo $customer_address; ?></td>
                        <td><?php echo $esewa; ?></td>
                        <td>
                            <a class="btn-secondary" href="<?php echo SITEURL; ?>admin/update-order.php?id=<?php echo $id; ?>"> Update Order</a>
                        </td>
                    </tr>
                    </tr>
            <?php
                }
            } else {
                echo "<tr><td colspan='12' class='error'>Orders Not available </td></tr>";
            }
            ?>



        </table>
    </div>
</div>
<?php include('partials/footer.php') ?>