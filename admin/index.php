<?php
include('include/header.php');

if(!isset($_SESSION['email'])){
    header('location:signin.php');
    exit(); 
}
if(isset($_SESSION['email'])){
    $email = $_SESSION['email'];
}

?>
<div class="container-fluid mt-2">
    <div class="row">
        
        <div class="col-md-3 col-lg-3">
            <?php require_once('include/sidebar.php'); ?>
        </div>
        
        <div class="col-md-9 col-lg-9">
            
            <div class="row">
               
                <?php
                
                $query = "SELECT COUNT(*) AS num_new_orders FROM customer_order WHERE order_status='pending'";
                $stmt = oci_parse($conn, $query);
                if (!$stmt) {
                    $e = oci_error($conn);  
                    echo htmlentities($e['message']);
                    exit();
                }
                
                $executed = oci_execute($stmt);
                if (!$executed) {
                    $e = oci_error($stmt);  
                    echo htmlentities($e['message']);
                    exit();
                }
                
                $row = oci_fetch_assoc($stmt);
                $num_new_orders = isset($row['NUM_NEW_ORDERS']) ? $row['NUM_NEW_ORDERS'] : 0;
                ?>
                <div class="col-xl-3 col-sm-6 mb-3">
                    <div class="card text-white bg-success o-hidden h-100">
                        <div class="card-body">
                            <div class="card-body-icon">
                                <i class="fad fa-shopping-cart fa-2x"></i>
                            </div>
                            <div class="mr-5"><span style="font-size:24px;"><?php echo $num_new_orders;?></span> Pending Orders</div>
                        </div>
                        <a class="card-footer text-white clearfix small z-1" href="pending_furniture_pro.php">
                            <span class="float-left">View Details</span>
                            <span class="float-right">
                                <i class="fas fa-angle-right"></i>
                            </span>
                        </a>
                    </div>
                </div>
                
                <?php
               
                $query = "SELECT COUNT(*) AS num_delivered_orders FROM customer_order WHERE order_status='delivered'";
                $stmt = oci_parse($conn, $query);
                if (!$stmt) {
                    $e = oci_error($conn);  
                    echo htmlentities($e['message']);
                    exit(); 
                }
                
                $executed = oci_execute($stmt);
                if (!$executed) {
                    $e = oci_error($stmt);  
                    echo htmlentities($e['message']);
                    exit();
                }
                
                $row = oci_fetch_assoc($stmt);
                $num_delivered_orders = isset($row['NUM_DELIVERED_ORDERS']) ? $row['NUM_DELIVERED_ORDERS'] : 0; // Set default value if key is undefined
                ?>
                <div class="col-xl-3 col-sm-6 mb-3">
                    <div class="card text-white bg-warning o-hidden h-100">
                        <div class="card-body">
                            <div class="card-body-icon">
                                <i class="fad fa-truck fa-2x"></i>
                            </div>
                            <div class="mr-5"><span style="font-size:24px;"><?php echo $num_delivered_orders;?> </span> Delivered Orders</div>
                        </div>
                        <a class="card-footer text-white clearfix small z-1" href="delivered_furniture_pro.php">
                            <span class="float-left">View Details</span>
                            <span class="float-right">
                                <i class="fas fa-angle-right"></i>
                            </span>
                        </a>
                    </div>
                </div>
              
                <?php
                
                $query = "SELECT COUNT(*) AS num_customers FROM customer";
                $stmt = oci_parse($conn, $query);
                if (!$stmt) {
                    $e = oci_error($conn);  
                    echo htmlentities($e['message']);
                    exit(); 
                }
                
                $executed = oci_execute($stmt);
                if (!$executed) {
                    $e = oci_error($stmt);  
                    echo htmlentities($e['message']);
                    exit(); 
                }
                
                $row = oci_fetch_assoc($stmt);
                $num_customers = isset($row['NUM_CUSTOMERS']) ? $row['NUM_CUSTOMERS'] : 0; 
                ?>
                <div class="col-xl-3 col-sm-6 mb-3">
                    <div class="card text-white bg-primary o-hidden h-100">
                        <div class="card-body">
                            <div class="card-body-icon">
                                <i class="fad fa-fw fa-users fa-2x"></i>
                            </div>
                            <div class="mr-5"><span style="font-size:24px;"><?php echo $num_customers;?></span> Active Customers</div>
                        </div>
                        <a class="card-footer text-white clearfix small z-1" href="customers.php">
                            <span class="float-left">View Details</span>
                            <span class="float-right">
                                <i class="fas fa-angle-right"></i>
                            </span>
                        </a>
                    </div>
                </div>
               
                <?php
               
                $query = "SELECT SUM(product_amount) AS total_earnings FROM customer_order";
                $stmt = oci_parse($conn, $query);
                if (!$stmt) {
                    $e = oci_error($conn);  
                    echo htmlentities($e['message']);
                    exit();
                }
                
                $executed = oci_execute($stmt);
                if (!$executed) {
                    $e = oci_error($stmt);  
                    echo htmlentities($e['message']);
                    exit();
                }
                
                $row = oci_fetch_assoc($stmt);
                $total_earnings = isset($row['TOTAL_EARNINGS']) ? $row['TOTAL_EARNINGS'] : 0; 
                ?>
                <div class="col-xl-3 col-sm-6 mb-3">
                    <div class="card text-white bg-danger o-hidden h-100">
                        <div class="card-body">
                            <div class="card-body-icon">
                                <i class="fad fa-sack fa-2x"></i>
                            </div>
                            <div class="mr-5"><span style="font-size:24px;"><?php echo $total_earnings; ?></span> ₹ Earned</div>
                        </div>
                        <a class="card-footer text-white clearfix small z-1" href="payment.php">
                            <span class="float-left">View Details</span>
                            <span class="float-right">
                                <i class="fas fa-angle-right"></i>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
          
        </div>
    </div>
</div>

<?php include('include/footer.php');?>
