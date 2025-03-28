<?php session_start();
      include('include/dbcon.php');
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Furniture Shop Management System | Admin - Dashboard</title>

  <link href="css/mdb.min.css" rel="stylesheet">
  <link href="css/bootstrap.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <style>
        
        @media (min-width:320px) and (max-width:768px){
            #image{
                background-image:none;
            }
        }
        @media (min-width:768px){
            #image{
                background-image:url('img/login_banner.jpg');
                height:100vh;
                background-size: 1000px 800px; 
                opacity:0.7;
            }
        }
 </style>
  
</head>


<div class="container-fluid">
  <div class="row">
    <div class="col-lg-6 col-md-0" id="image"></div>
    <div class="col-md-12 col-lg-6 col-12 mt-5">
      <div class="login d-flex align-items-center py-5">

        <div class="container mt-5">
          <div class="row">
            <div class="col-md-9 col-lg-8 mx-auto">
              <h1 class="login-heading text-center mb-2">Welcome on </h1>
              <h3 class="login-heading text-center mb-4">Furniture Shop Management System</h3>
              <form method="post">
              <?php
              if(isset($error)){
                      
                      echo "<div class='alert bg-danger' role='alert'>
                              <span class='text-white text-center'> $error</span>
                                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                  <span aria-hidden='true'>&times;</span>
                                </button>
                            </div>";
                  
                      }
                      ?>
                <div class="form-group">
                 <label for="email">Email address</label>
                 <input type="email" name="email" class="form-control" placeholder="Email address" required autofocus>
                </div>

                <div class="form-group">
                  <label for="password">Password</label>
                  <input type="password" name="pass_word" class="form-control" placeholder="Password" required>
                </div>

                
                <input class="btn btn-lg btn-primary btn-block btn-login text-uppercase font-weight-bold mb-2" type="submit" value="Sign in" name="signin">
                <div class="text-center">
                  <a class="small" href="forget_pass.php">Forgot password?</a></div>

              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php
if (isset($_POST['signin'])) {
    $email = $_POST['email'];    
    $password = $_POST['pass_word'];  

   
    $query = "SELECT * FROM admin WHERE email = :email AND pass_word = :pass_word";
    
   
    $stmt = oci_parse($conn, $query);
    oci_bind_by_name($stmt, ':email', $email);
    oci_bind_by_name($stmt, ':pass_word', $password);
    oci_execute($stmt);
    if ($row = oci_fetch_array($stmt, OCI_ASSOC)) {
        $db_admin_id = $row['ID'];
        $db_admin_name = $row['NAME'];
        $db_admin_email = $row['EMAIL'];
        $db_admin_password = $row['PASS_WORD'];

        if ($email == $db_admin_email && $password == $db_admin_password) {
            $_SESSION['id'] = $db_admin_id;
            $_SESSION['name'] = $db_admin_name;
            $_SESSION['email'] = $db_admin_email;
            $_SESSION['pass_word'] = $db_admin_password;
          echo '<p> successful </p>';
            header('location: index.php');
        } else {
            $error = "Invalid Email or Password";
        }
    } else {
        $error = "Invalid Email or Password";
    }
    oci_free_statement($stmt);
}

if (isset($error)) {
    echo $error;
}
?>

  </div>
  
</div>
