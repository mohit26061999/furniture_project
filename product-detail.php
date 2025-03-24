<?php include('include/header.php'); ?>

<div class="jumbotron">
    <h1 class="text-center mt-5">Product detail</h1>
</div>

<main>

    <div class="container">
        <center>
            <div class="w-75">
                <?php 
                if(isset($msg)){
                    echo $msg;
                }
                ?>
            </div>
        </center>
        
        <section class="my-5">
            <div class="row">

                <?php   
                if(isset($_GET['product_id'])){
                    $p_id = $_GET['product_id'];
                    
                    $pdetail_query = "SELECT * FROM furniture_product WHERE pid=$p_id";
                    $pdetail_stmt = oci_parse($conn, $pdetail_query);
                    oci_execute($pdetail_stmt);
                    $pdetail_row = oci_fetch_assoc($pdetail_stmt);

                    if($pdetail_row){
                        $pid = $pdetail_row['PID'];
                        $title = $pdetail_row['TITLE'];
                        $category = $pdetail_row['CATEGORY'];
                        $detail = $pdetail_row['DETAIL'];
                        $price = $pdetail_row['PRICE'];
                        $size = $pdetail_row['P_SIZE'];
                        $img1 = $pdetail_row['IMG'];
                    }
                }
                ?>
                <div class="col-md-5 mb-4 mb-md-0">
                    <div class="view zoom z-depth-2 rounded">
                        <img class="img-fluid w-100" src="img/<?php echo $img1; ?>" alt="Chair">
                    </div>
                </div>

                <div class="col-md-7">

                    <h5><?php echo $title; ?></h5>
                    <p class="mb-2 text-muted text-uppercase small">
                        <?php
                        $cat_query = "SELECT * FROM categories Where id=$category ORDER BY id ASC";
                        $cat_stmt = oci_parse($conn, $cat_query);
                        oci_execute($cat_stmt);
                        $cat_row = oci_fetch_assoc($cat_stmt);
                        if($cat_row){
                            echo  $cat_name = ucfirst($cat_row['CATEGORY']); 
                        }
                        ?>
                    </p>
                    <p><span class="mr-1"><strong>₹ <?php echo $price; ?></strong></span></p>
                    <p class="pt-1"><?php echo $detail; ?></p>
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <th class="pl-0 w-25" scope="row"><strong>Size</strong></th>
                                    <td><?php echo $size; ?></td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                    <hr>

                   
                    <form method="post">
                        <div class="form-group">
                            <label for="rating">Rating:</label>
                            <div class="star-rating">
                                <span class="star" data-rating="1">&#9733;</span>
                                <span class="star" data-rating="2">&#9733;</span>
                                <span class="star" data-rating="3">&#9733;</span>
                                <span class="star" data-rating="4">&#9733;</span>
                                <span class="star" data-rating="5">&#9733;</span>
                            </div>
                            <input type="hidden" name="rating" id="rating_input" value="0">
                        </div>
                        <div class="form-group">
                            <label for="comment">Comment:</label>
                            <textarea class="form-control" id="comment" name="coment" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" name="submit_review">Submit Review</button>
                    </form>
                    <?php



if(isset($_SESSION['email'])){
    
    $custid = $_SESSION['id'];
   
    if(isset($_POST['submit_review'])) {
        
        $rating = $_POST['rating'];
        $comment = $_POST['coment'];

      
        $query = "INSERT INTO product_reviews (product_id, customer_id, rating, coment) VALUES (:product_id, :customer_id, :rating, :coment)";
        $stmt = oci_parse($conn, $query);
        oci_bind_by_name($stmt, ':product_id', $p_id);
        oci_bind_by_name($stmt, ':customer_id', $custid); 
        oci_bind_by_name($stmt, ':rating', $rating);
        oci_bind_by_name($stmt, ':coment', $comment);

        if (oci_execute($stmt)) {
            $msg = "Review submitted successfully!";
        } else {
            $error = oci_error($stmt);
            $msg = "Error: " . $error['message'];
        }
    }
}
?>



<h3>Customer Reviews</h3>
<?php 
$product_id = $_GET['product_id'];
$reviews_query = "SELECT product_reviews.rating, product_reviews.comment, customer.cust_name AS customer_name
                  FROM product_reviews
                  INNER JOIN customer ON product_reviews.customer_id = customer.cust_id
                  WHERE product_reviews.product_id = $product_id";

$reviews_stmt = oci_parse($conn, $reviews_query);
oci_execute($reviews_stmt);
while($review_row = oci_fetch_assoc($reviews_stmt)) {
 
    $customer_name = $review_row['CUSTOMER_NAME'];
    $rating = $review_row['RATING'];
    $comment = $review_row['COMMENT'];

    $stars = '';
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $rating) {
            $stars .= '&#9733;'; 
        } else {
            $stars .= '&#9734;';
        }
    }

    echo "<p>Customer: $customer_name</p>";
    echo "<p>Rating: $stars</p>";
    echo "<p>Comment: $comment</p>";
    echo "<hr>";
}
?>






                </div>

            </div>

        </section>
      
        <section>

            <h3 class="text-center pt-5 mb-0">Related Products </h3>

           
            <div class="row mt-5 mb-4">

                <?php 
                $p_query = "SELECT * FROM furniture_product WHERE category LIKE '%$category%' order by title DESC FETCH FIRST 4 ROWS ONLY";
                $p_stmt = oci_parse($conn, $p_query);
                oci_execute($p_stmt);
                
                while($p_row = oci_fetch_assoc($p_stmt)){
                    $pid      = $p_row['PID'];
                    $ptitle  = $p_row['TITLE'];
                    $pcat    = $p_row['CATEGORY'];
                    $p_price = $p_row['PRICE'];
                    $size    = $p_row['P_SIZE'];
                    $img1    = $p_row['IMG'];
                    ?>

                    <div class="col-md-6 col-lg-3 mb-4">
                        <img src="img/<?php echo $img1; ?>" class="hover-effect" width="100%" height="190px">
                        <div class="text-center mt-3">
                            <h5 title="<?php echo $ptitle; ?>"><?php echo substr($ptitle,0,20); ?>...</h5>
                            <h6>Rs. <?php echo $p_price; ?></h6>
                        </div>

                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-12 text-center">

                                <a href="product-detail.php?product_id=<?php echo $pid;?>" type="submit" class="btn btn-primary btn-sm hover-effect">
                                    <i class="far fa-shopping-cart"></i>
                                </a>
                                <a href="product-detail.php?product_id=<?php echo $pid;?>" class="btn btn-default btn-sm hover-effect text-dark" >
                                    <i class="far fa-info-circle"></i> View Details
                                </a>

                            </div>

                        </div>
                    </div>

                <?php  
                }
                ?>

            </div>
           

        </section>
       
    </div>

</main>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const stars = document.querySelectorAll('.star');
        const ratingInput = document.getElementById('rating_input');

        stars.forEach(star => {
            star.addEventListener('click', () => {
                const rating = parseInt(star.getAttribute('data-rating'));
                ratingInput.value = rating;

                
                stars.forEach(s => {
                    if (parseInt(s.getAttribute('data-rating')) <= rating) {
                        s.classList.add('selected');
                    } else {
                        s.classList.remove('selected');
                    }
                });
            });
        });
    });
</script>
<style>
    .star-rating {
        font-size: 24px;
        cursor: pointer;
    }

    .star {
        color: #ccc;
        transition: color 0.2s;
    }

    .star.selected {
        color: gold;
    }
</style>
<?php 
  oci_close($conn);
  ?>

<?php include('include/footer.php');?>


