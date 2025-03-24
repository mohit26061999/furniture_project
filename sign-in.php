<?php
include('include/header.php'); ?>

        <div class="container sign-in-up">
          <div class="row mb-5" >
            <div class="col-md-6" >
              <h1>Online Furniture Store</h1>
              <p>An online furniture shop that allows users to check for various furniture available at the online 
                store and purchase online. The project consists of list of furniture products displayed in various
                 models and designs. The user may browse through these products as per categories. If the user likes 
                 a product he may add it to his shopping cart. Once user wishes to checkout he must 
                register on the site first. He can then login using same id password next time.</p>
            </div>
            
            
            <div class="col-md-6" style="height:66.5vh;">
              <div class="card">
                <div class="card-body">
                  <h1 class="text-center mt-5">Sign in</h1>
                  
                  <form method="post" class="mt-5 p-3">

                  <?php 
if(isset($_POST['signin'])){
    
    $email = $_POST['email'];    
    $password = $_POST['password'];    
    
    $query = "SELECT * FROM customer";
    $stmt = oci_parse($conn, $query);
    oci_execute($stmt);
    
    $found = false;
    
    while (($row = oci_fetch_assoc($stmt)) !== false) {
        $db_cust_id    = $row['CUST_ID'];
        $db_cust_name  = $row['CUST_NAME'];
        $db_cust_email = $row['CUST_EMAIL'];
        $db_cust_pass  = $row['CUST_PASS'];
        $db_cust_add   = $row['CUST_ADD'];
        $db_cust_city  = $row['CUST_CITY'];
        $db_cust_pcode = $row['CUST_POSTALCODE'];
        $db_cust_number= $row['CUST_NUMBER'];

        if($email == $db_cust_email && $password == $db_cust_pass){
            $_SESSION['id']    = $db_cust_id;
            $_SESSION['name']  = $db_cust_name;
            $_SESSION['email'] = $db_cust_email;
            $_SESSION['add']   = $db_cust_add;
            $_SESSION['city']  = $db_cust_city;
            $_SESSION['pcode'] = $db_cust_pcode;
            $_SESSION['number']= $db_cust_number;
            
            header('location:customer/index.php');
            $found = true; 
            break; 
        } 
    }
    
    if (!$found) {
        
        $error = "Invalid Email or Password";
    }
    
    oci_free_statement($stmt);
}

if(isset($error)){
    echo "<div class='alert bg-danger' role='alert'>
            <span class='text-white text-center'> $error</span>
            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                <span aria-hidden='true'>×</span>
            </button>
          </div>";
}
?>


                      
                    <div class="form-group">
                      <input type="text" name="email" placeholder="Email" class="form-control" required>
                     </div>
                     <div class="form-group">
                    <input type="password" name="password" placeholder="password" class="form-control" required>
                    </div>
                      
                    <a href="#" > Forget Password?</a>

                      <div class="form-group text-center mt-4">
                        <input type="submit" name="signin" class="btn btn-primary" value="Sign in">
                      </div>

                      <div class="text-center mt-4"> Not a Member Yet <a href="register.php"> Register </a></div>

                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
   <?php
   oci_close($conn);
   ?>

  <?php include('include/footer.php'); ?>