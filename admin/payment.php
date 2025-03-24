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
            <?php include("include/sidebar.php");?>
        </div>

        <div class="col-md-9">

            <div class="row">
              <div class="col-md-1">
                <i class="fad fa-truck fa-6x text-primary"></i>
              </div>
              <div class="col-md-11 text-left mt-4">
               <h1 class="ml-5 display-4 font-weight-normal">Payments:</h1>
              </div>
            </div>
           <hr>

        <table class="table table-responsive table-hover ">
                      <thead class="thead-light">
                      <tr>
    <th>#Invoice No.</th>
    <th>Order ID</th>
    <th>Product ID</th>
    <th>Customer ID</th>
    <th>Customer Email</th>
    <th>Price (₹)</th>
    <th>Quantity</th>
    <th>Order Status</th>
    <th>Order Date</th>
    <th>Payment Method</th>
    <th>Transaction Number</th>
</tr>

                      </thead>
                        <tbody class="text-center">
                        <?php

$order_query = "
    SELECT co.*, p.*
    FROM customer_order co
    INNER JOIN payments p ON co.customer_id = p.customer_id
    WHERE co.order_status='delivered'
";
$stmt = oci_parse($conn, $order_query);
oci_execute($stmt);


while ($order_row = oci_fetch_assoc($stmt)) {
    $order_invoice = $order_row['INVOICE_NO'];
    $order_id = $order_row['ORDER_ID'];
    $cust_id = $order_row['CUSTOMER_ID'];
    $cust_email = $order_row['CUSTOMER_EMAIL'];
    $order_pro_id = $order_row['PRODUCT_ID'];
    $order_qty = $order_row['PRODUCTS_QTY']; 
    $order_amount = $order_row['AMOUNT']; 
    $order_date = $order_row['ORDER_DATE'];
    $order_status = $order_row['ORDER_STATUS'];

   
    $payment_method = $order_row['PAYMENT_METHOD'];
    $transaction_number = $order_row['TRANSACTION_NUMBER'];

    ?>
    <tr>
        <td><?php echo $order_invoice; ?></td>
        <td><?php echo $order_id; ?></td>
        <td><?php echo $order_pro_id; ?></td>
        <td><?php echo $cust_id; ?></td>
        <td><?php echo $cust_email; ?></td>
        <td><?php echo $order_amount; ?></td>
        <td><?php echo $order_qty; ?></td>
        <td><?php echo "<i class='fad fa-truck text-primary'></i> " . ucfirst($order_status); ?></td>
        <td><?php echo $order_date; ?></td>
        <td><?php echo $payment_method; ?></td> 
        <td><?php echo $transaction_number; ?></td> 
    </tr>
    <?php
}
?>



                                    
                              
                          
                      </tbody>
                    </table>

        </div>
    </div>
</div>

<?php include("include/footer.php"); ?>

