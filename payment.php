<?php include('config/constant.php') ?>
<?php include('partial-front/menu.php') ?>
</form>
<?php  
   if(isset($_SESSION['id'])){
     $customer_id    = $_SESSION['id'];
     $customer_email = $_SESSION['email']; 
     $customer_name  = $_SESSION['username'];
     $customer_add   = $_SESSION['add'];  
     $customer_number= $_SESSION['number'];

     $sub_total=0;
     $shipping_cost = 0;
     $total = 0;

     if(isset($_POST['checkout'])){
        $fullname = $_POST['fullname'];
        $address  = $_POST['address'];
        $number   = $_POST['phone_number'];
        
        $order_date = date("Y-m-d h:i:s");
        $status = "Ordered";
    
        // Fetching customer contact from the session
        // Assuming 'number' is the session variable for customer contact
    
        $cartt = "SELECT * FROM cart WHERE user_id='$customer_id'";
        $run  = mysqli_query($con,$cartt);
        if(mysqli_num_rows($run) > 0){
            while($row = mysqli_fetch_array($run) ){
                $db_pro_id  = $row['product_id'];
                $db_pro_qty  = $row['quantity'];
               
    
                $pr_query  = "SELECT * FROM tbl_furniture WHERE id=$db_pro_id";
                $pr_run    = mysqli_query($con,$pr_query);
                if(mysqli_num_rows($pr_run) > 0){
                    while($pr_row = mysqli_fetch_array($pr_run)){
                        $price = $pr_row['price'];
                        $product_name=$pr_row['title'];

                        $product_id = $pr_row['id']; // Fetching product_id from the database
    
                        $single_pro_total_price = $db_pro_qty * $price;
                      }
                    }
                }
            }
        }
    }
?>
 <?php
                  $cart = "SELECT * FROM cart WHERE user_id='$customer_id'";
                  $run  = mysqli_query($con,$cart);
                   if(mysqli_num_rows($run) > 0){
                      while($cart_row = mysqli_fetch_array($run)){
                          $db_cust_id = $cart_row['user_id'];
                          $db_pro_id  = $cart_row['product_id'];
                          $db_pro_qty  = $cart_row['quantity'];

                       $pr_query  = "SELECT * FROM tbl_furniture WHERE id=$db_pro_id";
                       $pr_run    = mysqli_query($con,$pr_query);
                                       
                        if(mysqli_num_rows($pr_run) > 0){
                         while($pr_row = mysqli_fetch_array($pr_run)){
                              $pid = $pr_row['id'];
                              $title = $pr_row['title'];
                              $price = $pr_row['price'];
                              $arrPrice = array($pr_row['price']);    
                             
                              $img1 = $pr_row['image_name'];
                                             
                              $single_pro_total_price = $db_pro_qty * $price;
                              $pro_total_price = array($db_pro_qty * $price);  
                              $each_pr = implode($pro_total_price);
                                           //   $values = array_sum($arrPrice);
                                 $shipping_cost=0;
                                 $values = array_sum($pro_total_price);
                                 $sub_total +=$values;
                                 $total = $sub_total + $shipping_cost;
                                }
                            }    
                          }
                         }            
                            ?> 
      <form action="https://uat.esewa.com.np/epay/main" method="POST">
    <input value="<?php echo $total;?>" name="tAmt" type="hidden">
    <input value="<?php echo $total;?>" name="amt" type="hidden">
    <input value="0" name="txAmt" type="hidden">
    <input value="0" name="psc" type="hidden">
    <input value="0" name="pdc" type="hidden">
    <input value="EPAYTEST" name="scd" type="hidden">
    <input value="<?php echo $pid;?>" name="pid" type="hidden">
    <input value="http://localhost/FurnitureStore/esewa_payment_success.php?" type="hidden" name="su">
    <input value="http://localhost/FurnitureStore/esewa_payment_failed.php?" type="hidden" name="fu">
    <input value="Pay with E-sewa" type="submit">
    </form>

        </div>

        <div class="checkout-cart">
        <h2>Order Detail</h2><hr>
      
        <table class="checkout-tbl">
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <tr>
                <td><img class="checkout-img" src="Image/furniture/<?php echo $img1;?>" alt=""></td>
                <td><?php echo $title;?></td>
                <td>x <?php echo $db_pro_qty;?></td>
                <td> <?php echo $single_pro_total_price;?></td>
            
            </tr>
           
               
        </table>
        <div class="checkout-total">
            <div class="checkout-total-info">
                <p>Subtotal</p>
                <p>Shipping</p><hr>
                <h3>TOTAL</h3>
            </div>
            <div class="checkout-total-calculate">
                <p>Rs.<?php echo $sub_total;?></p>
                <p>Rs.<?php echo $shipping_cost;?></p><hr>
                <h3>Rs.<?php echo $total;?></h3>
            </div>
        </div>
     </div>
 
    </div>

<?php include('partial-front/footer.php') ?>