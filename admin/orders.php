<?php
include('include/header.php');


if(!isset($_SESSION['email'])){
    header('location: newpro/sign-in.php');
    exit(); 
}
?>
    <div class="jumbotron bg-secondary">
        <h1 class="text-center text-white mt-5">My Orders</h1>
    </div>
     
     <div class="container mt-5 mb-5">
      
      <div class="row">

         <div class="col-md-3">
          <?php include('include/sidebar.php');?>
        </div>

         <div class="col-md-9">
          <h3>My Orders:</h3><hr>
          <?php
            $customer_id = $_SESSION['id'];

            $order_query = "SELECT * FROM customer_order WHERE customer_id = :customer_id";
            $stmt = oci_parse($conn, $order_query);
            oci_bind_by_name($stmt, ":customer_id", $customer_id);
            oci_execute($stmt);
            if ($stmt) {
                $num_rows = oci_num_rows($stmt); 
                if ($num_rows > 0) {
                    if (isset($_SESSION['message'])) {
                        echo $_SESSION['message'];
                    }
                } else {
                    echo "<h2 class='text-center text-secondary mt-5 mb-5'>You haven't ordered anything yet </h2>";
                }
          ?>
                <table class="table table-responsive table-hover ">
                    <thead class="thead-light">
                        <tr>
                            <th>#Invoice</th>
                            <th width="120px">Product image</th>
                            <th>Product name</th>
                            <th>Product quantity</th>
                            <th>Total Price (₹)</th>
                            <th>Date</th>
                            <th width="120px">Status</th>
                        </tr>
                    </thead>
                    <tbody>
    <?php
    while ($order_row = oci_fetch_assoc($stmt)) {
        $order_invoice = $order_row['INVOICE_NO'];
        $order_pro_id = $order_row['PRODUCT_ID'];
        $order_qty = $order_row['PRODUCTS_QTY'];
        $order_amount = $order_row['PRODUCT_AMOUNT'];
        $order_date = $order_row['ORDER_DATE'];
        $order_status = $order_row['ORDER_STATUS'];

        $pro_query = "SELECT * FROM furniture_product WHERE pid = :order_pro_id";
        $stmt_pro = oci_parse($conn, $pro_query);
        oci_bind_by_name($stmt_pro, ":order_pro_id", $order_pro_id);
        oci_execute($stmt_pro);

        while ($pr_row = oci_fetch_assoc($stmt_pro)) {
            $title = $pr_row['TITLE'];
            $img1 = $pr_row['IMAGE'];
    ?>
            <tr>
                <td>#<?php echo $order_invoice; ?></td>
                <td>
                    <img src="newpro/img/<?php echo $img1; ?>" width="100%">
                </td>
                <td>
                    <h6><?php echo $title; ?></h6>
                </td>
                <td>x <?php echo $order_qty; ?></td>
                <td><?php echo $order_amount; ?> </td>
                <td><?php echo $order_date; ?></td>
                <td><?php
                    if ($order_status == 'pending') {
                        echo "<i class='far fa-exclamation-circle text-warning'></i> $order_status";
                    } else if ($order_status == 'verified') {
                        echo "<i class='far fa-check-circle text-success'></i> $order_status";
                    } else if ($order_status == 'delivered') {
                        echo "<i class='far fa-truck text-primary'></i> $order_status";
                    }
                    ?></td>
            </tr>
    <?php
        }
    }
    ?>
</tbody>
                </table>
          <?php
            } else {
                echo "<h2 class='text-center text-secondary mt-5 mb-5'>You haven't ordered anything yet </h2>";
            }
          ?>

          
         </div>
       </div>

     </div>
      
   
     <?php include('include/footer.php');?>
