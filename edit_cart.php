<?php include('include/header.php'); ?>
        
        <div class="jumbotron bg-primary">
            <h2 class="text-center mt-5 text-white">Edit Cart</h2>
        </div>
        
        <div class="container">
        <?php  


if(isset($_SESSION['id'])){
    $customer_id = $_SESSION['id'];
    
    if(isset($_GET['cart_id'])){
        $edit_cart = $_GET['cart_id'];
        
     
        if(isset($_POST['update'])){
            $qty = $_POST['Qty'];

            $up_query = "UPDATE cart SET quantity=:qty WHERE product_id = :edit_cart";
            $stmt_up = oci_parse($conn, $up_query);
            oci_bind_by_name($stmt_up, ':qty', $qty);
            oci_bind_by_name($stmt_up, ':edit_cart', $edit_cart);
            oci_execute($stmt_up);
        }
     
        $cart_query = "SELECT * FROM cart WHERE cust_id=:customer_id AND product_id = :edit_cart";
        $stmt_cart = oci_parse($conn, $cart_query);
        oci_bind_by_name($stmt_cart, ':customer_id', $customer_id);
        oci_bind_by_name($stmt_cart, ':edit_cart', $edit_cart);
        oci_execute($stmt_cart);

        $sub_total = 0;
        $shipping_cost = 0;
        $total = 0;
        ?>
        <div class="row">
           
            <div class="col-md-9 p-3">
                <h5>Shopping Cart</h5>
                <p class="text-right" style="margin-top:-30px"><a href="cart.php"><i class="fas fa-shopping-cart"></i> Go to Cart</a> </p>
                <hr>
                <form method="post">
                    <table class="table table-responsive">
                        <thead class="thead-light">
                            <tr>
                                <th colspan="2">Product Detail</th>
                                <th>Quantity</th>
                                <th>Price (₹)</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while($cart_row = oci_fetch_assoc($stmt_cart)){
                                $db_pro_id = $cart_row['PRODUCT_ID'];
                                $db_pro_qty = $cart_row['QUANTITY'];

                                $pr_query = "SELECT * FROM furniture_product WHERE pid=:db_pro_id";
                                $stmt_pr = oci_parse($conn, $pr_query);
                                oci_bind_by_name($stmt_pr, ':db_pro_id', $db_pro_id);
                                oci_execute($stmt_pr);

                                while($pr_row = oci_fetch_assoc($stmt_pr)){
                                    $pid = $pr_row['PID'];
                                    $title = $pr_row['TITLE'];
                                    $price = $pr_row['PRICE'];
                                    $size = $pr_row['P_SIZE'];
                                    $img1 = $pr_row['IMAGE'];

                                    $single_pro_total_price = $db_pro_qty * $price;
                                    $sub_total += $single_pro_total_price;
                                    ?>
                                    <tr>
                                        <td width="150px">
                                            <img src="img/<?php echo $img1;?>" width="100%">
                                        </td>
                                        <td>
                                            <h5><?php echo $title;?></h5>
                                            <p> Dimension: <?php echo $size;?></p>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" name="Qty" value="<?php echo $db_pro_qty;?>"> 
                                        </td>
                                        <td><?php echo $price;?></td>
                                        <td><?php echo $single_pro_total_price;?> </td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                    <input type="submit" name="update" class="btn btn-primary float-right" value="Update">
                </form>
            </div>
        
            <div class="col-md-3 p-3">
                <h5>Order Detail</h5>
                <hr>
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-6">
                        <h6>Subtotal</h6>
                        <h6>Shipping</h6>
                        <h5 class="font-weight-bold">Total</h5>
                    </div>
                    <div class="col-md-6 col-sm-6 col-6">
                        <h6 class="text-right font-weight-normal">₹ <?php echo $sub_total;?></h6>
                        <h6 class="text-right font-weight-normal">₹ <?php echo $shipping_cost;?></h6>
                        <h5 class="text-right font-weight-bold">₹ <?php echo $total;?></h5>
                    </div>
                </div>
            </div>
          
        </div>

    <?php
    }
}
?>

        </div>
        <?php 
  oci_close($conn);
  ?>

  <?php include('include/footer.php');?>