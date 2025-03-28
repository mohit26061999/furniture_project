<?php 
ob_start();
require_once('include/header.php');

if(!isset($_SESSION['email'])){
    header('location: login.php');
} 

if(isset($_GET['edit'])){
    $edit = $_GET['edit'];
    $query ="SELECT * FROM categories WHERE id = :edit";
    $stmt = oci_parse($conn, $query);
    oci_bind_by_name($stmt, ':edit', $edit);
    oci_execute($stmt);
    $row = oci_fetch_assoc($stmt);
    $dbcat_id = $row['ID'];
    $dbcat = $row['CATEGORY'];
}

if(isset($_POST['submit'])){
    $category = $_POST['catname'];

    if($category == $dbcat){
        $error = "Category already exists!";
    }
    
    $query ="UPDATE categories SET category = :category WHERE id = :dbcat_id";
    $stmt = oci_parse($conn, $query);
    oci_bind_by_name($stmt, ':category', $category);
    oci_bind_by_name($stmt, ':dbcat_id', $dbcat_id);
    if(oci_execute($stmt)){
        $msg = "Category updated successfully!";
        header("location:");
    } else {
        $error = "Not updated";
    }
}
?>
<div class="container-fluid mt-2">
    <div class="row">
        <div class="col-md-3 col-lg-3">
            <?php require_once('include/sidebar.php'); ?>
        </div>
        <div class="col-md-9 col-lg-9">
            <div class="row">
                <div class="col-md-2">
                    <i class="fad fa-couch text-primary" style="font-size:70px;"></i>
                </div>
                <div class="col-md-10 mt-1">
                    <h1 style="font-size:50px;">Edit Furniture Category</h1>
                </div>
            </div>
            <hr>
            <div class="row mt-5">
                <div class="col-md-8">
                    <?php if(isset($msg)){echo "<span class='text-success' style='font-weight:bold;'>$msg</span>";}?>
                    <form action="" method="post">
                        <div class="row">
                            <div class="col-md-3">
                                <input type="text" name="cid" class="form-control" disabled placeholder="Edit id" value="<?php echo $dbcat_id;?>">
                            </div>
                            <div class="col-md-9">
                                <input type="text" name="catname" class="form-control" placeholder="Edit Category" value="<?php echo $dbcat;?>">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <input type="submit" name="submit" class="btn btn-primary btn-block" value="Edit Category">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php 
  oci_close($conn);
  ?>