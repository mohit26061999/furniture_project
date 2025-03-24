<?php include('include/header.php'); ?>
        
<div class="jumbotron">
    <h2 class="text-center mt-5">Checkout</h2>
</div>

<div class="container">
<?php  

    if(isset($_SESSION['id'])){
        $customer_id    = $_SESSION['id'];
        
        $customer_name  = $_SESSION['name'];
        $customer_add   = $_SESSION['add'];  
        $customer_city  = $_SESSION['city']; 
        $customer_pcode = $_SESSION['pcode'];
        $customer_number= $_SESSION['number'];

        $sub_total=0;
        $shipping_cost = 0;
        $total = 0;

        
        $customer_query = "SELECT cust_email FROM customer WHERE cust_id = :customer_id";
        $customer_stmt = oci_parse($conn, $customer_query);
        oci_bind_by_name($customer_stmt, ':customer_id', $customer_id);
        oci_execute($customer_stmt);
        $customer_row = oci_fetch_assoc($customer_stmt);
        $customer_email = $customer_row['CUST_EMAIL'];

        if(isset($_POST['checkout'])){
            $fullname = $_POST['fullname'];
            $address  = $_POST['address'];
            $city     = $_POST['city'];
            $code     = $_POST['code'];
            $number   = $_POST['phone_number'];
            $invoice  = rand();
            $date     = date("y-m-d"); 

            $cart_query = "SELECT * FROM cart WHERE cust_id=:customer_id";
            $cart_stmt = oci_parse($conn, $cart_query);
            oci_bind_by_name($cart_stmt, ':customer_id', $customer_id);
            oci_execute($cart_stmt);

            while($row = oci_fetch_assoc($cart_stmt)){
                $db_pro_id  = $row['PRODUCT_ID'];
                $db_pro_qty  = $row['QUANTITY'];
                

                $pr_query  = "SELECT * FROM furniture_product WHERE pid=:pid";
                $pr_stmt = oci_parse($conn, $pr_query);
                oci_bind_by_name($pr_stmt, ':pid', $db_pro_id);
                oci_execute($pr_stmt);

                while($pr_row = oci_fetch_assoc($pr_stmt)){
                    $price = $pr_row['PRICE'];
                    $single_pro_total_price = $db_pro_qty * $price;
                   
                    
                    $checkout_query = "INSERT INTO customer_order (
                        customer_id, 
                        customer_email, 
                        customer_fullname, 
                        customer_address, 
                        customer_city, 
                        customer_pcode, 
                        customer_phonenumber, 
                        product_id, 
                        product_amount, 
                        invoice_no, 
                        products_qty, 
                        order_date, 
                        order_status
                      ) VALUES (
                        :customer_id, 
                        :customer_email, 
                        :fullname, 
                        :address, 
                        :city, 
                        :pcode, 
                        :phonenumber, 
                        :db_pro_id, 
                        :single_pro_total_price, 
                        :invoice, 
                        :db_pro_qty, 
                        TO_DATE(:order_date, 'YYYY-MM-DD'), 
                        'pending' 
                      )";
                    $checkout_stmt = oci_parse($conn, $checkout_query);
                    oci_bind_by_name($checkout_stmt, ':customer_id', $customer_id);
                    oci_bind_by_name($checkout_stmt, ':customer_email', $customer_email); // Use fetched customer_email
                    oci_bind_by_name($checkout_stmt, ':fullname', $fullname);
                    oci_bind_by_name($checkout_stmt, ':address', $address);
                    oci_bind_by_name($checkout_stmt, ':city', $city);
                    oci_bind_by_name($checkout_stmt, ':pcode', $code);
                    oci_bind_by_name($checkout_stmt, ':phonenumber', $number);
                    oci_bind_by_name($checkout_stmt, ':db_pro_id', $db_pro_id);
                    oci_bind_by_name($checkout_stmt, ':single_pro_total_price', $single_pro_total_price);
                    oci_bind_by_name($checkout_stmt, ':invoice', $invoice);
                    oci_bind_by_name($checkout_stmt, ':db_pro_qty', $db_pro_qty);
                    oci_bind_by_name($checkout_stmt, ':order_date', $date);
                    

                    oci_execute($checkout_stmt);

                    $error = oci_error($checkout_stmt);
                    if ($error) {
                        echo "Error: " . $error['message'];
                    } else {
                        echo "Data inserted successfully";
                        oci_commit($conn);
                    }
                    

                }
            }

            $_SESSION['message'] = "<div class='alert alert-primary alert-dismissible fade show pt-1 pb-1 pl-3' role='alert'><strong><i class='fas fa-check-circle'></i> Thanks! </strong>for your order, It will be deliver within 7 working days.<button type='button' class='close p-2' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
            header('location: payment.php?');
        }
?>
        <h1>Check Out</h1>
        <div class="row">

            <div class="col-md-6 p-3">
                <h5>Shipping Detail</h5><hr>
                <div class="form-group">
                    <label for="email"><b>Email:</b></label>
                    <label><b><?php echo $customer_email;?></b></label>
                </div>

                <form method="post"  class="mt-4">
                    <div class="form-group">
                        <label for="fullname">Fullname:</label>
                        <input type="text" name="fullname" placeholder="Full Name" class="form-control" value="<?php echo $customer_name; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="address">Address:</label>
                        <input type="text" name="address" placeholder="Address" value="<?php echo $customer_add; ?>" class="form-control" >
                    </div>
                      
                    <div class="row">
                        <div class="col-md-6 col-6">
                            <div class="form-group">
                                <label for="city">City:</label>
                                <input type="text" name="city" placeholder="City" class="form-control" value="<?php echo $customer_city; ?>" required >
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-6">
                            <div class="form-group">
                                <label for="postalcode">Postal code:</label>
                                <input type="number" name="code" placeholder="Postal code" class="form-control" value="<?php echo $customer_pcode; ?>" required >
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="number">Number:</label>
                        <input type="number" name="phone_number" placeholder="Phone Number" class="form-control" value="<?php echo $customer_number; ?>" required>
                    </div>

                    <div class="form-group text-center mt-4">
                        <input type="submit" name="checkout" class="btn btn-primary btn-block p-2" value="Place Order" id="border-less">
                    </div>
                </form>
            </div>

            <div class="col-md-6 p-3">

                <table class="table table-responsive table-hover ">
                    <h5>Order Detail</h5><hr>
                    <tbody>
<?php
        $cart_query = "SELECT * FROM cart WHERE cust_id=:customer_id";
        $cart_stmt = oci_parse($conn, $cart_query);
        oci_bind_by_name($cart_stmt, ':customer_id', $customer_id);
        oci_execute($cart_stmt);

        while($row = oci_fetch_assoc($cart_stmt)){
            $db_pro_id  = $row['PRODUCT_ID'];
            $db_pro_qty  = $row['QUANTITY'];

            $pr_query  = "SELECT * FROM furniture_product WHERE pid=:pid";
            $pr_stmt = oci_parse($conn, $pr_query);
            oci_bind_by_name($pr_stmt, ':pid', $db_pro_id);
            oci_execute($pr_stmt);

            while($pr_row = oci_fetch_assoc($pr_stmt)){
                $pid = $pr_row['PID'];
                $title = $pr_row['TITLE'];
                $price = $pr_row['PRICE'];
                $size = $pr_row['P_SIZE'];
                $img1 = $pr_row['IMG'];

                $single_pro_total_price = $db_pro_qty * $price;
                $pro_total_price = $db_pro_qty * $price;
                $shipping_cost=0;
                $sub_total += $pro_total_price;
                $total = $sub_total + $shipping_cost;
                $_SESSION['total'] = $total;
?>
                <div class="row">
                 
                    <div class="col-md-3 col-3">
                        <img src="img/<?php echo $img1;?>" width="100%">
                    </div>
                   
                    <div class="col-md-5 col-5">
                        <h5><?php echo $title;?> </h5>
                        <p> Dimension:<?php echo $size;?></p>
                    </div>
                    
                    <div class="col-md-2 col-1">
                        <h5>x <?php echo $db_pro_qty;?></h5>
                    </div>
                  
                    <div class="col-md-2 col-2">
                        <h5><?php echo $single_pro_total_price;?></h5>
                    </div>
                 
                </div><hr>
<?php  
            }
        }
?>
                    </tbody>
                </table>
              

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
    
    
    
?>
</div>
<?php 
  oci_close($conn);
  ?>
<?php include('include/footer.php');?>
