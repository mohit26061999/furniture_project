<?php 
include('include/header.php');

if(!isset($_SESSION['email'])){
    header('location:../sign-in.php');
}

if(isset($_SESSION['email'])){
  $customer_id    = $_SESSION['id'];
}
?>

   <div class="jumbotron bg-secondary">
      <h1 class="text-center text-white mt-5">Account Detail</h1>
    </div>

   <div class="container mt-5">
       <div class="row">

        <div class="col-md-3">
          <?php include('include/sidebar.php');?>
        </div>

        <div class="col-md-9">
          <h3>Access Details:</h3><hr>
          <h6>CHANGE PASSWORD</h6>
           <p>If you wish to change the password to access your account, please provide
            the following information:</p>
          
            
              <?php
              if(isset($_POST['update'])){
                $old_pass     = $_POST['old_pass'];
                $new_pass     = $_POST['new_pass'];
                $confirm_pass = $_POST['conf_pass'];

                $query = "SELECT cust_pass FROM customer WHERE cust_id = :customer_id";
                $stmt = oci_parse($conn, $query);
                oci_bind_by_name($stmt, ":customer_id", $customer_id);
                oci_execute($stmt);

                $row = oci_fetch_assoc($stmt);
                $cust_pass = $row['CUST_PASS'];

                if(!empty($old_pass) && !empty($new_pass) && !empty($confirm_pass)){
                  if($old_pass === $cust_pass){
                    if($new_pass === $confirm_pass){
                      $up_query = "UPDATE customer SET cust_pass = :confirm_pass WHERE cust_id = :customer_id";
                      $stmt_up = oci_parse($conn, $up_query);
                      oci_bind_by_name($stmt_up, ":confirm_pass", $confirm_pass);
                      oci_bind_by_name($stmt_up, ":customer_id", $customer_id);

                      if(oci_execute($stmt_up)){
                        $msg = "<div class='alert alert-success alert-dismissible fade show pt-1 pb-1 pl-3'  role='alert'>
                                 <strong><i class='fas fa-check-circle'></i> Congratulation! </strong> your password has been changed.
                                 <button type='button' class='close p-2' data-dismiss='alert' aria-label='Close'>
                                  <span  aria-hidden='true'>&times;</span>
                                 </button>
                               </div>";
                      } else {
                        $error = "<div class='alert alert-danger alert-dismissible fade show pt-1 pb-1 pl-3'  role='alert'>
                                   <strong><i class='fas fa-info-circle'></i> Oops! </strong> An error occurred while updating the password.
                                   <button type='button' class='close p-2' data-dismiss='alert' aria-label='Close'>
                                    <span  aria-hidden='true'>&times;</span>
                                   </button>
                                 </div>";
                      }
                    } else {
                      $error = "<div class='alert alert-danger alert-dismissible fade show pt-1 pb-1 pl-3'  role='alert'>
                                 <strong><i class='fas fa-info-circle'></i> Oops! </strong> New password and confirm password must match.
                                 <button type='button' class='close p-2' data-dismiss='alert' aria-label='Close'>
                                  <span  aria-hidden='true'>&times;</span>
                                 </button>
                               </div>";
                    }
                  } else {
                    $error = "<div class='alert alert-danger alert-dismissible fade show pt-1 pb-1 pl-3'  role='alert'>
                               <strong><i class='fas fa-info-circle'></i> Oops! </strong> Old password is incorrect.
                               <button type='button' class='close p-2' data-dismiss='alert' aria-label='Close'>
                                <span  aria-hidden='true'>&times;</span>
                               </button>
                             </div>";
                  }
                } else {
                  $error = "<div class='alert alert-danger alert-dismissible fade show pt-1 pb-1 pl-3'  role='alert'>
                             <strong><i class='fas fa-info-circle'></i> Oops! </strong> All fields are required.
                             <button type='button' class='close p-2' data-dismiss='alert' aria-label='Close'>
                              <span  aria-hidden='true'>&times;</span>
                             </button>
                           </div>";
                }

                if(isset($msg)){
                  echo $msg;
                } else if(isset($error)){
                  echo $error;
                }
              }
              ?>
              
            <form  method="post" class="w-50">

                <div class="form-group">
                  <label>Old Password: *</label>
                  <input type="text" name="old_pass" placeholder="Old Password" class="form-control" >
               </div>

                <div class="form-group">
                  <label>New Password: *</label>
                  <input type="text" name="new_pass" placeholder="New Password" class="form-control" >
                </div>

                <div class="form-group">
                  <label>Confirm Password: *</label>
                  <input type="text" name="conf_pass" placeholder="Confirm Password"  class="form-control" >
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
