<?php
include('include/header.php');
include('include/dbcon.php');

if (isset($_SESSION['total'])) {

    $total = $_SESSION['total'];
} else {
   
    $total = 0;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
    $custtid=$_SESSION['id'];
    $paymentMethod = $_POST['payment_method'];
    $total = $_POST['amount'];

    $sql = "INSERT INTO payments (payment_method, amount,customer_id) 
            VALUES (:paymentMethod, :total,:custtid)";

    $stmt = oci_parse($conn, $sql);

    oci_bind_by_name($stmt, ':paymentMethod', $paymentMethod);
    oci_bind_by_name($stmt, ':total', $total);
    oci_bind_by_name($stmt, ':custtid', $custtid);

    $success = oci_execute($stmt);

    if ($success) {
 
        if (isset($_SESSION['id'])) {
            $customer_id = $_SESSION['id'];
            $delete_sql = "DELETE FROM cart WHERE cust_id = :customer_id";
            $delete_stmt = oci_parse($conn, $delete_sql);
            oci_bind_by_name($delete_stmt, ':customer_id', $customer_id);
            oci_execute($delete_stmt);
            oci_free_statement($delete_stmt);
        }

        header("Location: customer/orders.php");
        exit;
    } else {
        $e = oci_error($stmt);
        echo "Error: " . htmlentities($e['message'], ENT_QUOTES);
    }

    oci_free_statement($stmt);
}
?>

<div class="jumbotron">
    <h2 class="text-center mt-5">Payment</h2>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h4 class="m-0">Payment Options</h4>
                </div>
                <div class="card-body">
                    <form method="post" action="upi.php">
                        <input type="hidden" name="payment_method" value="upi">
                        <input type="hidden" name="amount" value="<?php echo $total; ?>">
                        <button type="submit" class="btn btn-primary btn-block">Pay with UPI</button>
                    </form>
                    <br>
                    <form method="post" action="">
                        <input type="hidden" name="payment_method" value="card">
                        <input type="hidden" name="amount" value="<?php echo $total; ?>">
                        <button type="submit" class="btn btn-success btn-block">Pay with Credit/Debit Card</button>
                    </form>
                    <br>
                    <form method="post" action="">
                        <input type="hidden" name="payment_method" value="paypal">
                        <input type="hidden" name="amount" value="<?php echo $total; ?>">
                        <button type="submit" class="btn btn-info btn-block">Pay with PayPal</button>
                    </form>
                    <br>

                    <form method="post" >
                        <input type="hidden" name="payment_method" value="cash_on_delivery">
                        <input type="hidden" name="amount" value="<?php echo $total; ?>">
                        <button type="submit" class="btn btn-warning btn-block">Cash on Delivery</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h4 class="m-0">Payment Summary</h4>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Total Amount: ₹ <?php echo $total; ?></h5>
                    
                </div>
            </div>
        </div>
    </div>
</div>
<?php 
  oci_close($conn);
  ?>
<?php
include('include/footer.php');
?>
