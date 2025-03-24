<?php 

include('include/header.php');

if (!isset($_SESSION['email'])) {
    header('location:../sign-in.php');
    exit(); 
}

?>
<div class="jumbotron bg-secondary">
    <h1 class="text-center text-white mt-5">My Orders</h1>
</div>

<div class="container mt-5 mb-5">

    <div class="row">

        <div class="col-md-3">
            <?php include('include/sidebar.php'); ?>
        </div>

        <div class="col-md-9">
            <h3>My Orders:</h3><hr>
            <?php 
            $customer_id = $_SESSION['id'];

            
            if (!$conn) {
                echo "Connection error: " . oci_error();
                exit(); 
            }

            $order_query = "SELECT * FROM customer_order WHERE customer_id=:customer_id";
            $order_stmt = oci_parse($conn, $order_query);
            if (!$order_stmt) {
                $error = oci_error($conn);
                echo "Order statement error: " . $error['message'];
                exit(); 
            }

            oci_bind_by_name($order_stmt, ":customer_id", $customer_id);
            $execute = oci_execute($order_stmt);
            if (!$execute) {
                $error = oci_error($order_stmt);
                echo "Order execution error: " . $error['message'];
                exit(); 
            }

            if (oci_fetch_all($order_stmt, $orders, null, null, OCI_FETCHSTATEMENT_BY_ROW)) {
                if (isset($_SESSION['message'])) {
                    echo $_SESSION['message'];
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
                        foreach ($orders as $order_row) {
                            $order_invoice = $order_row['INVOICE_NO'];
                            $order_pro_id = $order_row['PRODUCT_ID'];
                            $order_qty = $order_row['PRODUCTS_QTY'];
                            $order_amount = $order_row['PRODUCT_AMOUNT'];
                            $order_date = $order_row['ORDER_DATE'];
                            $order_status = $order_row['ORDER_STATUS'];

                            $pro_query = "SELECT * FROM furniture_product WHERE pid=:order_pro_id";
                            $pro_stmt = oci_parse($conn, $pro_query);
                            oci_bind_by_name($pro_stmt, ":order_pro_id", $order_pro_id);
                            oci_execute($pro_stmt);

                            if (oci_fetch_all($pro_stmt, $products, null, null, OCI_FETCHSTATEMENT_BY_ROW)) {
                                foreach ($products as $pr_row) {
                                    $title = $pr_row['TITLE'];
                                    $img1 = $pr_row['IMG'];

                                    ?>
                                    <tr>
                                        <td>#<?php echo $order_invoice; ?></td>
                                        <td>
                                            <img src="../img/<?php echo $img1; ?>" width="100%">
                                        </td>
                                        <td>
                                            <h6><?php echo $title; ?></h6>
                                        </td>
                                        <td>x <?php echo $order_qty; ?></td>
                                        <td><?php echo $order_amount; ?></td>
                                        <td><?php echo $order_date; ?></td>
                                        <td><?php 
                                            if ($order_status == 'pending') {
                                                echo "<i class='far fa-exclamation-circle text-warning'></i> $order_status";
                                            } else if ($order_status == 'verified') {
                                                echo "<i class='far fa-check-circle text-success'></i> $order_status";
                                            } else if ($order_status == 'delivered') {
                                                echo "<i class='far fa-truck text-primary'></i> $order_status";
                                            }
                                            ?> 
                                        </td>
                                    </tr>   
                                <?php
                                }
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

<?php 



include('include/footer.php');

if (isset($_POST['payment_method'])) {
    if ($_POST['payment_method'] == 'cash_on_delivery') {
        // Process cash on delivery payment logic here

        // Delete items from cart after successful payment
        $del_query = "DELETE FROM cart WHERE cust_id = :customer_id";
        $del_stmt = oci_parse($conn, $del_query);
        oci_bind_by_name($del_stmt, ':customer_id', $customer_id);
        oci_execute($del_stmt);
        unset($_POST['payment_method']);
        // Redirect to orders.php after payment
        header("Location: ./customer/orders.php");
        exit;
    } else {
        // Handle unsupported or invalid payment methods
        echo "Unsupported payment method";
    }
}


?>
 <?php 
  oci_close($conn);
  ?>
