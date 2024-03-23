<?php include('config/constant.php'); ?>
<?php include('partial-front/menu.php'); ?>
<style>
    .category-div {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        column-gap: 15px;
        row-gap: 15px;
        margin: 30px 15px;

    }

    .explore-div {
        margin-top: 100px;
        margin-left: 45%;
    }

    .explore-furniture {
        color: white;
        background-color: rgb(235, 91, 7);
        padding: 15px;
        border-radius: 5px;
        box-shadow: 1px 1px 5px rgba(146, 127, 127, 0.5);
    }

    .explore-furniture:hover {
        background-color: #fb5607;
    }
</style>
<h3 class="explore-div"><a class="explore-furniture" href="">Explore Furniture</a></h3>
<div class="category-div" data-aos="fade-up" data-aos-delay="100">
    <?php
    $sql = "SELECT * FROM tbl_category WHERE active='Yes' AND featured='Yes'";
    $res = mysqli_query($con, $sql);
    $count = mysqli_num_rows($res);
    if ($count > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $id = $row['id'];
            $title = $row['title'];
            $image_name = $row['image_name'];
    ?>
            <a href="<?php echo SITEURL; ?>category-furniture.php?category_id=<?php echo $id; ?>">
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
<?php include('partial-front/footer.php'); ?>