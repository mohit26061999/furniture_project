<?php  
require_once('include/header.php');

if(!isset($_SESSION['email'])){
  header('location: signin.php');
  exit(); 
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
                    <i class="fad fa-users fa-6x text-primary"></i>
                </div>
                <div class="col-md-11 text-left mt-4">
                    <h1 class="ml-5 display-4 font-weight-normal">View All Customers:</h1>
                </div>
            </div>
            <hr>
            <table class="table table-responsive table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>#Id</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Password</th>
                        <th>Address</th>
                        <th>City</th>
                        <th>Postal code</th>
                        <th>Contact Number</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                  
                    $query = "SELECT * FROM customer";
                    $stmt = oci_parse($conn, $query);

                   
                    oci_execute($stmt);

                  
                    while($row = oci_fetch_array($stmt, OCI_ASSOC)) {
                        $cust_id = $row['CUST_ID'];
                        $cust_name = $row['CUST_NAME'];
                        $cust_email = $row['CUST_EMAIL'];
                        $cust_pass = $row['CUST_PASS'];
                        $cust_add = $row['CUST_ADD'];
                        $cust_city = $row['CUST_CITY'];
                        $cust_postalcode = $row['CUST_POSTALCODE'];
                        $cust_number = $row['CUST_NUMBER'];
                    ?>
                            <tr>
                                <td><?php echo $cust_id; ?></td>
                                <td width="150px"><?php echo $cust_name; ?></td>
                                <td><?php echo $cust_email; ?></td>
                                <td><input type="password" value="<?php echo $cust_pass; ?>" disabled></td>
                                <td><?php echo $cust_add; ?></td>
                                <td><?php echo $cust_city; ?></td>
                                <td><?php echo $cust_postalcode; ?></td>
                                <td><?php echo $cust_number; ?></td>
                            </tr>
                    <?php
                    }

                    oci_free_statement($stmt);
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php 
  oci_close($conn);
  ?>

<?php 
require_once('include/footer.php');
?>
