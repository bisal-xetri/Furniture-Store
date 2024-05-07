<?php include('config/constant.php'); ?>
<?php include('partial-front/menu.php'); ?>
<?php
// Start or resume the session

// Check if the user is logged in
if (isset($_SESSION['username'])) {
  // Check if 'id' session variable is set
  if (isset($_SESSION['id'])) {
    $custid = $_SESSION['id'];

    if (isset($_GET['cart_id'])) {
      $p_id = $_GET['cart_id'];

      $sel_cart = "SELECT * FROM cart WHERE user_id = $custid AND product_id = $p_id";
      $run_cart = mysqli_query($con, $sel_cart);

      if ($run_cart) {
        if (mysqli_num_rows($run_cart) == 0) {
          $cart_query = "INSERT INTO `cart`(`user_id`, `product_id`,quantity) VALUES ($custid,$p_id,1)";
          if (mysqli_query($con, $cart_query)) {
            header('location:index.php');
            exit; // Exit after redirection
          }
        } else {
          while ($row = mysqli_fetch_array($run_cart)) {
            $exist_pro_id = $row['product_id'];
            if ($p_id == $exist_pro_id) {
              $error = "<script> alert('⚠️ This product is already in your cart  ');</script>";
            }
          }
        }
      } else {
        // Handle query execution failure
        echo "Error executing query: " . mysqli_error($con);
      }
    }
  } else {
    // Handle 'id' session variable not being set
    echo "Warning: 'id' session variable is not set";
  }
} else {
  echo "<script> function a(){alert('⚠️ Login is required to add this product into cart');}</script>";
}
?>

<div class="swiper mySwiper">
  <div class="swiper-wrapper">
    <div class="swiper-slide"><img src="Image/wallpaper3.jpg" alt="" srcset=""></div>
    <div class="swiper-slide"><img src="Image/wallpaper2.jpg" alt=""></div>
    <div class="swiper-slide"><img src="Image/w3.jpg" alt=""></div>

  </div>

  <div class="swiper-pagination"></div>
</div>
<?php
if (isset($_SESSION['order'])) {
  echo $_SESSION['order'];
  unset($_SESSION['order']);
}
?>
</div>
<div class=explore-div>
  <h3><a class="explore" href="furniture-items.php">Explore Now</a></h3>
</div>
<div class="category-container" data-aos="fade-up" data-aos-delay="100">
  <?php
  $sql = "SELECT * FROM tbl_category WHERE active='Yes' AND featured='Yes'  LIMIT 3";
  $res = mysqli_query($con, $sql);
  $count = mysqli_num_rows($res);
  if ($count > 0) {
    while ($row = mysqli_fetch_assoc($res)) {
      $id = $row['id'];
      $title = $row['title'];
      $image_name = $row['image_name'];
  ?>
      <a href="<?php SITEURL; ?>category-furniture.php?category_id=<?php echo $id; ?>">
        <div class="category-img">
          <?php
          if ($image_name == '') {
            echo "<div class='error'>Image not available</div>";
          } else {
            //img available
          ?>
            <img src="<?php echo SITEURL; ?>Image/Category/<?php echo $image_name; ?> " alt="">
          <?php
          }
          ?>
          <div class="category-name"><?php echo $title; ?></div>
        </div>
      </a>
  <?php
    }
  } else {
    echo "<div class='error'> Category not available</div>";
  }
  ?>
</div>
<div class=explore-div>
  <h3><a class="explore" href="">Latest Products</a></h3>
</div>
<div class="chair-type" >
  <?php
  $sql2 = "SELECT * 
  FROM tbl_furniture 
  WHERE active='YES' AND Featured='YES' 
  ORDER BY id DESC 
  LIMIT 5;";
  $res2 = mysqli_query($con, $sql2);
  $count2 = mysqli_num_rows($res2);
  if ($count2 > 0) {
    while ($row = mysqli_fetch_assoc($res2)) {
      $id = $row['id'];
      $title = $row['title'];
      $price = $row['price'];
      $image_name = $row['image_name'];
  ?>
      <div class="chair-info"data-aos="fade-up" data-aos-delay="100">
        <div class="chair-picture">
          <?php
          //check img available
          if ($image_name == '') {
            echo "<div class='error'>Image not available</div>";
          } else {
          ?>
            <a href="<?php echo SITEURL; ?>furniture-detail.php?image_id=<?php echo $id; ?>">
              <img src="<?php echo SITEURL; ?>Image/furniture/<?php echo $image_name; ?>" alt="" />
            </a>
          <?php
          }

          ?>

        </div>
        <div class="chair-description">
          <p class="chair-name"><?php echo $title; ?></p>
          <p class="chair-price">Rs.<?php echo $price; ?></p>
          <a href="<?php echo SITEURL;  ?>order.php?furniture_id=<?php echo  $id; ?>" onclick="a()" class="buy">buy</a>
          <a href="index.php?cart_id=<?php echo $id; ?>" class="add-to-cart js-add-to-cart" onclick="a()">add to cart</a>

        </div>
      </div>
  <?php
    }
  } else {
    echo "<div class='error'> Furniture Not available</div>";
  }
  ?>


</div>
<div class="seemore">
  <a href="furniture-items.php">see more...</a>
</div>
<h1 class="work-title">We Deal With</h1>

    <div class="how-it-work"  data-aos="fade-up" data-aos-delay="100">
   
       
        <div class="choose-product product">
            <figure  >
            <img class="work-img" src="bedimage/king bed.webp" alt="">
        </figure>
            <h3>Modern Bed Set Designs</h3>
          
            <p>We deal with modern bedset design made from manufactured wood with solid wood veneers.Affordable price with high quality products.</p>

        </div>
        <div class="receive-product product">
            <figure>
                <img class="work-img" src="chairimage/slipperchair.webp" alt="">
            </figure>
            <h3>Modern Arm Chairs Design</h3>
           
            <p>We deal with All Modern Style Arm Chairs It oozes class and style and is very chic and modern in design, constructed from a durable solid wood.</p>

        </div>
        <div class="pay-product product">
            <figure>
                <img class="work-img" src="Image/table.jpg" alt="">
            </figure>
            <h3>New Table Designs</h3>
     
            <p>We deal with All Modern Style tables It oozes class and style and is very chic and modern in design, constructed from a durable solid wood.</p>

        </div>
        
    </div>
    <div class="how-it-work" data-aos="fade-up" data-aos-delay="100">
   
       
   <div class="choose-product product">
       <figure  >
       <img class="work-img" src="Image/modernsofa.jpg" alt="">
   </figure>
       <h3>Modern Sofa Designs</h3>
     
       <p>We deal with All Modern Style Sofas/Couches ,very chic and modern in design, constructed from a durable solid wood.</p>

   </div>
   <div class="receive-product product">
       <figure>
           <img class="work-img" src="Image/newcupboard.jpg" alt="">
       </figure>
       <h3>New Stylish Cupboard Designs</h3>
      
       <p>We deal with All Modern Style Cupboards, built-in cupboards are storage space that forms part of the design of the room.</p>

   </div>
   <div class="pay-product product">
       <figure>
           <img class="work-img" src="Image/dinningset.jpg" alt="">
       </figure>
       <h3>Modern Dining Set</h3>

       <p> We deal with dining Table It oozes class and style and is very chic and modern in design. Dining table constructed from a durable solid wood.</p>

   </div>
   
</div>

<h1 class="work-title" >How It Does Work</h1>

    <div class="how-it-work" data-aos="fade-up" data-aos-delay="100">
   
       
        <div class="choose-product product">
            <figure>
            <img  src="Image/mobileandlaptop.png" alt="">
        </figure>
            <h3>Product</h3>
            <p>Choose your own product</p>
            <p>Add product to cart or direct click on buy & proceed to checkout and put your shipping address then checkout that.</p>

        </div>
        <div class="receive-product product">
            <figure>
                <img src="Image/receive.png" alt="">
            </figure>
            <h3>Receive</h3>
            <p>Receive Your Product</p>
            <p>After checkout from cart your product will be deliver at your door step within 7 working days</p>

        </div>
        <div class="pay-product product">
            <figure>
                <img  src="Image/wallet.png" alt="">
            </figure>
            <h3>Pay</h3>
            <p>Cash on delivery or pay with E-sewa</p>
            <p> Receive your products then pay cash on that moment or direct pay and order.</p>

        </div>
    </div>
<?php include('partial-front/footer.php'); ?>