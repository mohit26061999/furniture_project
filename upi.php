<?php 

include('include/header.php');

if (isset($_SESSION['total'])) {
   
    $total = $_SESSION['total'];
} else {
   
    $total = 0; 
}
?>
<div class="jumbotron">
    <h2 class="text-center mt-5">UPI Payment</h2>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h4 class="m-0">UPI Payment Details</h4>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Total Amount: ₹ <?php echo $total; ?></h5>
                    <p class="card-text">To proceed with UPI payment, scan the QR code below or use the UPI ID provided.</p>
                   
                    <?php
                    
                    include('qr_code/qr_img.php');
                  
                    $upiId = "mohitkumar@paytm"; 
                  
                    echo '<img src="';
                    
                    echo QRcode::png("upi://pay?pa=$upiId&pn=Merchant Name&am=$total&cu=INR", false, QR_ECLEVEL_L, 5);
                    echo '" alt="UPI QR Code" />';
                    ?>
                </div>
                <div class="card-footer">
                    
                    <a href="payment.php" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('include/footer.php'); ?>
