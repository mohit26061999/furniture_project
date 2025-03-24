<?php include('include/header.php'); ?>

<div class="jumbotron b">
    <h2 class="text-center mt-5">Cart</h2>
</div>

<?php

if (isset($_SESSION['id'])) {
    $customer_id = $_SESSION['id'];


    if (isset($_GET['pid'])) {
        $proid = $_GET['pid'];
        $del_query = "DELETE FROM cart WHERE product_id = $proid AND cust_id = $customer_id";
        $stmt = oci_parse($conn, $del_query);
        oci_execute($stmt);
        header("location:cart.php");
    }

    $cart_query = "SELECT * FROM cart WHERE cust_id='$customer_id'";
    $stmt = oci_parse($conn, $cart_query);
    oci_execute($stmt);

    $sub_total = 0;
    $shipping_cost = 0;
    $total = 0;
    ?>

    <div class="container">
        <div class="row">
        
            <div class="col-md-9 p-3">
                <h5>Shopping Cart</h5><hr>
                <table class="table table-responsive table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th colspan="2">Product Detail</th>
                            <th>Quantity</th>
                            <th>Price (₹)</th>
                            <th>Total</th>
                            <th colspan="4">Actions(Edit/Del)</th>
                            <th colspan="4"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($cart_row = oci_fetch_array($stmt, OCI_ASSOC + OCI_RETURN_NULLS)) {
                            $db_cust_id = $cart_row['CUST_ID'];
                            $db_pro_id = $cart_row['PRODUCT_ID'];
                            $db_pro_qty = $cart_row['QUANTITY'];

                            $pr_query = "SELECT * FROM furniture_product WHERE pid=$db_pro_id";
                            $pr_stmt = oci_parse($conn, $pr_query);
                            oci_execute($pr_stmt);

                            while ($pr_row = oci_fetch_array($pr_stmt, OCI_ASSOC + OCI_RETURN_NULLS)) {
                                $pid = $pr_row['PID'];
                                $title = $pr_row['TITLE'];
                                $price = $pr_row['PRICE'];
                                $arrPrice = array($pr_row['PRICE']);
                                $size = $pr_row['P_SIZE'];
                                $img1 = $pr_row['IMG'];

                                $single_pro_total_price = $db_pro_qty * $price;
                                $pro_total_price = array($db_pro_qty * $price);
                                $shipping_cost = 0;
                                $values = array_sum($pro_total_price);
                                $sub_total += $values;
                                $total = $sub_total + $shipping_cost;
                                ?>
                                <tr>
                                    <td width="150px">
                                        <img src="img/<?php echo $img1; ?>" width="100%">
                                    </td>
                                    <td>
                                        <h5><?php echo $title; ?></h5>
                                        <p> Dimension: <?php echo $size; ?></p>
                                    </td>
                                    <td>
                                        x <?php echo $db_pro_qty; ?>
                                    </td>
                                    <td><?php echo $price; ?></td>
                                    <td><?php echo $single_pro_total_price; ?> </td>
                                    <td colspan="20" class="text-center">
                                        <a title="Edit Product" href="edit_cart.php?cart_id=<?php echo $pid; ?>" class="btn btn-primary btn-sm">
                                            <i class="fal fa-edit"></i>
                                        </a>
                                        <a title="Delete Product" href="cart.php?pid=<?php echo $pid; ?>" class="btn btn-danger btn-sm">X </a>
                                    </td>
                                </tr>
                            <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
           
            <div class="col-md-3 p-3">
                <h5>Order Detail</h5><hr>
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-6">
                        <h6>Subtotal</h6>
                        <h6>Shipping</h6>
                        <h5 class="font-weight-bold">Total</h5>
                    </div>
                    <div class="col-md-6 col-sm-6 col-6">
                        <h6 class="text-right font-weight-normal">₹ <?php echo $sub_total; ?></h6>
                        <h6 class="text-right font-weight-normal">₹ <?php echo $shipping_cost; ?></h6>
                        <h5 class="text-right font-weight-bold">₹ <?php echo $total; ?></h5>
                    </div>
                </div>
            </div>
      
        </div>

        <div class="container">
            <div class="row">
                <div class="col-md-5 col-6 text-left">
                    <a href="product.php">
                        <input class="btn btn-primary pt-2 pb-2" type="button" style="font-size:12px;" value="Continue Shopping">
                    </a>
                </div>
                <div class="col-md-4 col-6 text-right">
                    <a href="checkout.php">
                        <input type="button" name="proceed" value="Proceed checkout" style="font-size:12px;" class="btn btn-success pt-2 pb-2">
                    </a>
                </div>
            </div>
        </div>
    </div>

<?php } else {
    echo "<h2 class='text-center text-secondary mt-5' style='height:57vh; font-size:48px;'><i class='fad fa-shopping-cart text-primary'></i> Your Cart is Empty</h2>";
} ?>
 <?php 
  oci_close($conn);
  ?>
<?php include('include/footer.php'); ?>
