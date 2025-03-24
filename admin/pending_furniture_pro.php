<?php
include("include/header.php");

if (!isset($_SESSION['email'])) {
    header('location: signin.php');
    exit();
}



?>

<div class="container-fluid mt-2">
    <div class="row">
        <div class="col-md-3">
            <?php include("include/sidebar.php"); ?>
        </div>

        <div class="col-md-9">
            <div class="row">
                <div class="col-md-1">
                    <i class="fad fa-box-alt fa-6x text-warning"></i>
                </div>
                <div class="col-md-11 text-left mt-4 ">
                    <h1 class="ml-3 display-4 font-weight-normal">Pending Orders:</h1>
                </div>
            </div>
            <hr>
            <form method="post">
                <table class="table table-responsive table-hover ">
                    <thead class="thead-light">
                        <tr>
                            <th>#Invoice No.</th>
                            <th>Order ID</th>
                            <th>Product_id</th>
                            <th>Product Image</th>
                            <th>Product Category</th>
                            <th>Customer Id</th>
                            <th>Customer Email</th>
                            <th>Price (₹)</th>
                            <th>Quantity</th>
                            <th>Order_Status</th>
                            <th>Order_Date</th>
                            <th>Change Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        <?php
                        
                        $order_query = "SELECT * FROM customer_order WHERE order_status='pending'";
                        $stmt = oci_parse($conn, $order_query);
                        oci_execute($stmt);

                        while ($order_row = oci_fetch_assoc($stmt)) {
                        
                            $order_invoice = $order_row['INVOICE_NO'];
                            $order_id = $order_row['ORDER_ID'];
                            $cust_id = $order_row['CUSTOMER_ID'];
                            $cust_email = $order_row['CUSTOMER_EMAIL'];
                            $order_pro_id = $order_row['PRODUCT_ID'];
                            $order_qty = $order_row['PRODUCTS_QTY'];
                            $order_amount = $order_row['PRODUCT_AMOUNT'];
                            $order_date = $order_row['ORDER_DATE'];
                            $order_status = $order_row['ORDER_STATUS'];

                            $pr_query = "SELECT * FROM furniture_product WHERE pid = :order_pro_id";
                            $pr_stmt = oci_parse($conn, $pr_query);
                            oci_bind_by_name($pr_stmt, ':order_pro_id', $order_pro_id);
                            oci_execute($pr_stmt);
                            $pr_row = oci_fetch_assoc($pr_stmt);
                            $image = $pr_row['IMG'];
                            $category = $pr_row['CATEGORY']; 
                            ?>
                            <tr>
                                <td><?php echo $order_invoice; ?></td>
                                <td><?php echo $order_id; ?></td>
                                <td><?php echo $order_pro_id; ?></td>
                                <td width="120px"><img src="img/<?php echo $image; ?>" width="100%"></td>
                                <td><?php echo $category; ?></td>
                                <td><?php echo $cust_id; ?></td>
                                <td><?php echo $cust_email; ?></td>
                                <td><?php echo $order_amount; ?></td>
                                <td><?php echo $order_qty; ?></td>
                                <td><?php echo $order_status; ?></td>
                                <td><?php echo $order_date; ?></td>
                                <td><a href="edit_furn_verify_pen.php?order_id=<?php echo $order_id; ?>"><button type="button" class="btn btn-primary btn-sm"> Edit</button></a></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>

<?php include("include/footer.php"); ?>
