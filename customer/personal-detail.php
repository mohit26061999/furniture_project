<?php 
include('include/header.php');

if(!isset($_SESSION['email'])){
    header('location:../sign-in.php');
}

if(isset($_SESSION['email'])){
    $customer_id = $_SESSION['id'];

    $query = "SELECT * FROM customer WHERE cust_id=:customer_id";
    $stmt = oci_parse($conn, $query);
    oci_bind_by_name($stmt, ":customer_id", $customer_id);
    oci_execute($stmt);
    $row = oci_fetch_assoc($stmt);

    $cust_name = $row['CUST_NAME'];
    $cust_email = $row['CUST_EMAIL'];
    $cust_add = $row['CUST_ADD'];
    $cust_city = $row['CUST_CITY'];
    $cust_pcode = $row['CUST_POSTALCODE'];
    $cust_number = $row['CUST_NUMBER'];

    if(isset($_POST['update'])){
        $fullname = $_POST['fullname'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $city = $_POST['city'];
        $code = $_POST['code'];
        $number = $_POST['phone_number'];
       
        $up_query = "UPDATE customer 
                     SET cust_name=:fullname,
                         cust_add=:address,
                         cust_city=:city,
                         cust_postalcode=:code,
                         cust_number=:number
                     WHERE cust_id=:customer_id";

        $stmt = oci_parse($conn, $up_query);
        oci_bind_by_name($stmt, ":fullname", $fullname);
        oci_bind_by_name($stmt, ":address", $address);
        oci_bind_by_name($stmt, ":city", $city);
        oci_bind_by_name($stmt, ":code", $code);
        oci_bind_by_name($stmt, ":number", $number);
        oci_bind_by_name($stmt, ":customer_id", $customer_id);

        if(oci_execute($stmt)){
            $_SESSION['msg'] = "<div class='alert alert-success alert-dismissible fade show pt-1 pb-1 pl-3' role='alert'>
                                    <strong><i class='fas fa-check-circle'></i> Congratulation! </strong>Your Account has been updated.
                                    <button type='button' class='close p-2' data-dismiss='alert' aria-label='Close'>
                                        <span aria-hidden='true'>&times;</span>
                                    </button>
                                </div>";
            header('location:personal-detail.php');
        }
    }
}
?>






<div class="jumbotron bg-secondary">
   <h1 class="text-center text-white mt-5">Personal Detail</h1>
 </div>

<div class="container mt-5">
    <div class="row">

     <div class="col-md-3">
     <?php include('include/sidebar.php');?>
     </div>

     <div class="col-md-9">
       <h3>Personal Details:</h3><hr>
       <h6>CHANGE PERSONAL DETAILS</h6>
        <p>You can access and modify your personal details (name, billing address, telephone number, etc.) 
          in order to facilitate your future
           purchases and to notify us of any change in your contact details.</p>
          
          <?php 
          
               if(isset($_SESSION['msg'])){
                 echo $_SESSION['msg'];
                }
               ?>
            
          <form method="post" class="w-75">
            
            <div class="form-group ">
              <input type="text" name="fullname" placeholder="Full Name" value="<?php echo $cust_name;?>" class="form-control" >
             </div>

            <div class="form-group">
              <input type="text" name="email" placeholder="Email" class="form-control" value="<?php echo $cust_email;?>" disabled>
             </div>
          

              <div class="form-group">
                <input type="text" name="address" placeholder="Address" value="<?php echo $cust_add;?>" class="form-control" >
            </div>
             
            <div class="row">
              <div class="col-md-6 col-6">
                <div class="form-group">
                  <input type="text" name="city" placeholder="City" value="<?php echo $cust_city;?>" class="form-control" >
               </div>
              </div>
              
              <div class="col-md-6 col-6">
                <div class="form-group">
                  <input type="number" name="code" placeholder="Postal code" value="<?php echo $cust_pcode;?>" class="form-control" >
               </div>
              </div>

            </div>

            <div class="form-group">
              <input type="number" name="phone_number" placeholder="Phone Number" value="<?php echo $cust_number;?>" class="form-control" >
           </div>

              <div class="form-group text-center mt-4">
                <input type="submit" name="update" class="btn btn-primary" value="Update">
              </div>

          </form> 
        
      </div>
    </div>
</div>
<?php 
  oci_close($conn);
  ?>
        
<?php include('include/footer.php');?>