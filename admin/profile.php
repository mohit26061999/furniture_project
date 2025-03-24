<?php
require_once('include/header.php');

if (!isset($_SESSION['email'])) {
    header('location: signin.php');
}

if (isset($_SESSION['email'])) {
    $session_id = $_SESSION['id'];
    $session_email = $_SESSION['email'];
}

?>

<div class="container-fluid mt-2">
    <div class="row">
        <div class="col-md-3 col-lg-3">
            <?php require_once('include/sidebar.php'); ?>
        </div>

        <div class="col-md-9 col-lg-9">

            <div class="row">
                <div class="col-md-1">
                    <i class="fad fa-user-cog fa-6x text-primary"></i>
                </div>
                <div class="col-md-11 text-left mt-4">
                    <h1 class="ml-5 display-4 font-weight-normal">Profile Setting:</h1>
                </div>
            </div>
            <hr>

            
            <form method="post" enctype="multipart/form-data">
                <?php
             
                $message = '';

              
                if (isset($_POST['submit'])) {
                   
                    $name = $_POST['name'];
                    $password = $_POST['password'];
                    $image = $_FILES['upload']['name'];
                    $tmp_image = $_FILES['upload']['tmp_name'];

                   
                    if (empty($image)) {
                      
                        $u_query = "UPDATE admin SET name = :name, pass_word = :pass_word WHERE id = :session_id";
                    } else {
                     
                        $u_query = "UPDATE admin SET name = :name, pass_word = :pass_word, image = :image WHERE id = :session_id";
                    }

                   
                    $stmt = oci_parse($conn, $u_query);
                    oci_bind_by_name($stmt, ':name', $name);
                    oci_bind_by_name($stmt, ':pass_word', $password);
                    oci_bind_by_name($stmt, ':session_id', $session_id);

                    
                    if (!empty($image)) {
                        oci_bind_by_name($stmt, ':image', $image);
                    }
                    if (oci_execute($stmt)) {
                        $message = "Profile Has Been Updated";

                  
                        if (!empty($image) && move_uploaded_file($tmp_image, "img/" . $image)) {
                            header('location:' . $_SERVER['PHP_SELF']);
                            exit; // Stop further execution after redirection
                        }
                    } else {
                    
                        $message = "Failed to update profile. Please try again.";
                    }
                }

              
             
                $query = "SELECT * FROM admin WHERE id=:session_id ";
                 $stmt = oci_parse($conn, $query);
                oci_bind_by_name($stmt, ':session_id', $session_id);

               
                if (!$stmt) {
                    $error = oci_error($conn);
                    echo "Failed to prepare query: " . $error['message'];
                } else {
                    oci_bind_by_name($stmt, ':session_id', $session_id);
                    if (!oci_execute($stmt)) {
                        $error = oci_error($stmt);
                        echo "Failed to execute query: " . $error['message'];
                    } else {
                       
                        if ($row = oci_fetch_assoc($stmt)) {
                           
                            $db_name = $row['NAME'];
                            $db_email = $row['EMAIL'];
                            $db_password = $row['PASS_WORD'];
                            $db_image = $row['IMAGE'];
                        } else {
                            
                            $message = "User data not found.";
                        }
                    }
                }
                ?>

                <div class="row">
                    <?php if (isset($message)) {
                        echo "<p style='color:green; font-weight:bold;'>$message</p>";
                    }
                    ?>
                </div>

                <div class="col-md-12 mt-4">
                    <label for="name" class="font-weight-bold">Email:</label> <?php echo isset($db_email) ? $db_email : ""; ?>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name" class="font-weight-bold">Name:</label>
                            <input type="text" name="name" value="<?php echo isset($db_name) ? $db_name : ""; ?>" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="name" class="font-weight-bold">Password:</label>
                            <input type="password" name="password" value="<?php echo isset($db_password) ? $db_password : ""; ?>" class="form-control" id="inputPassword4MD" placeholder="Password">
                        </div>

                        <span>Choose files</span>
                        <input class="form-control-file border" type="file" name="upload">
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <img src="img/<?php echo isset($db_image) ? $db_image : ""; ?>" class="mt-4" width="50%">
                        </div>
                    </div>
                </div>

                <input type="submit" name="submit" class="btn btn-primary btn-md" value="Submit">
            </form>
        </div>
    </div>

</div>

<?php require_once('include/footer.php'); ?>
