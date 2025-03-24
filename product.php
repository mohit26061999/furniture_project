<?php
include('include/header.php');

$connection = oci_connect('sys', 'mohit', "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=$host)(PORT=1521))(CONNECT_DATA=(SERVICE_NAME=$sid)))",null,OCI_SYSDBA);


if (!$connection) {
    $error_message = oci_error();
    trigger_error('Could not connect to database: ' . $error_message['message'], E_USER_ERROR);
}

if(isset($_GET['page'])){
    $page_id = $_GET['page'];
} else {
    $page_id = 1;
}

$required_pro = 12;

$query = "SELECT * FROM (SELECT fp.*, ROWNUM AS rn FROM furniture_product fp WHERE stts = 'publish' ORDER BY pid) WHERE rn BETWEEN :start_row AND :end_row";
$statement = oci_parse($connection, $query);
$start_row = (($page_id - 1) * $required_pro) + 1;
$end_row = $start_row + $required_pro - 1;
oci_bind_by_name($statement, ":start_row", $start_row);
oci_bind_by_name($statement, ":end_row", $end_row);
oci_execute($statement);

$count_rows = oci_fetch_all($statement, $result);

$pages = ceil($count_rows / $required_pro);
$product_start = ($page_id - 1) * $required_pro;  

if(isset($_SESSION['id'])){
    $custid = $_SESSION['id'];
  
    if(isset($_GET['cart_id'])){
        $p_id = $_GET['cart_id'];
  
        $sel_cart = "SELECT * FROM cart WHERE cust_id = :custid and product_id = :p_id";
        $statement = oci_parse($connection, $sel_cart);
        oci_bind_by_name($statement, ":custid", $custid);
        oci_bind_by_name($statement, ":p_id", $p_id);
        oci_execute($statement);

        $num_rows = oci_fetch_all($statement, $result);

        if($num_rows == 0){
            $cart_query = "INSERT INTO cart(cust_id, product_id, quantity) VALUES (:custid, :p_id, 1)";
            $statement = oci_parse($connection, $cart_query);
            oci_bind_by_name($statement, ":custid", $custid);
            oci_bind_by_name($statement, ":p_id", $p_id);
            oci_execute($statement);
            header("location:product.php");
        } else {
            $error="<script>alert('⚠️ This product is already in your cart  '); </script>";
        }
    }
} else if(!isset($_SESSION['email'])){
    echo "<script> function a(){alert('⚠️ Login is required to add this product into cart');}</script>";
}

?>

<div class="jumbotron">
    <h2 class="text-center mt-5">Choose Products</h2>
</div>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-3 col-12">
           
        </div>

        <div class="col-md-9 col-12">
            <div class="row">
              
            </div>

       
            <?php 
            if(isset($msg)){
                echo $msg;
            } else if(isset($error)){
                echo $error;
            }
            ?>

            <div class="row">
                <?php   
                
              $statement = oci_parse($connection, $query);
              oci_bind_by_name($statement, ":start_row", $start_row);
              oci_bind_by_name($statement, ":end_row", $end_row);
              oci_execute($statement);
              
              while($p_row = oci_fetch_array($statement)){
                  $pid = $p_row['PID'];
                  $ptitle = $p_row['TITLE'];
                  $pcat = $p_row['CATEGORY'];
                  $p_price = $p_row['PRICE'];
                  $size = $p_row['P_SIZE'];
                  $img1 = $p_row['IMG'];
              ?>
              <div class="col-md-4 mt-4">
    <!-- Product card -->
    <div class="card">
        <img src="img/<?php echo $img1; ?>" class="card-img-top" alt="<?php echo $ptitle; ?>">
        <div class="card-body">
            <h5 class="card-title"><?php echo $ptitle; ?></h5>
            <p class="card-text">Price: Rs. <?php echo $p_price; ?></p>
            <a href="product.php?cart_id=<?php echo $pid;?>" type="submit" onclick="a()" class="btn btn-primary btn-sm hover-effect">
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
            
            <ul class="pagination pagination-md mt-5">
                <?php for($i=1; $i <= $pages; $i++ ){
                    echo "<li class='page-item ".($i == $page_id ? ' active ' : '')."'><a class='page-link' href='product.php?page=$i'>$i</a></li>";
                }?>
            </ul>
           

        </div>
    </div>
</div>
<?php 
  oci_close($conn);
  ?>
<?php include('include/footer.php'); ?>
