<?php 
require_once('include/header.php');
if(!isset($_SESSION ['email'])){
    header('location: signin.php');
}

?>

<div class="container-fluid mt-2">
    <div class="row">
        <div class="col-md-3 col-lg-3">
            <?php require_once('include/sidebar.php'); ?>
        </div>
        <?php
if (isset($_GET['del'])) {
    $del = $_GET['del'];
    
    $query = "DELETE FROM furniture_product WHERE pid = :del";
    $stmt = oci_parse($conn, $query);
    oci_bind_by_name($stmt, ':del', $del);
 
    oci_execute($stmt);
}
?>

<div class="col-md-9 col-lg-9">
    <div class="col-md-9">
        <div class="row">
            <div class="col-md-1">
                <i class="fad fa-th-list fa-6x text-primary"></i>
            </div>
            <div class="col-md-11 text-left mt-4">
                <h1 class="ml-5 display-4 font-weight-normal">Add Product:</h1>
            </div>
        </div>
        <hr>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="row">
                <?php
                if (isset($_POST['submit']))
                if(isset($_POST['product_size']) && isset($_POST['product_status'])){
                    $title = $_POST['title'];
                    $category = $_POST['category'];
                    $product_size = $_POST['product_size'];
                    $price = $_POST['price'];
                    $detail = $_POST['detail'];
                    $product_status = $_POST['product_status'];
                    $date_added = $_POST['date_added'];
                    $date = new DateTime($date_added);
                    $formatted_date = $date->format('d-M-Y');
                    $product_image = $_FILES['upload']['name'];
                    $tmp_product_image = $_FILES['upload']['tmp_name'];

                    $path = "img/" . $product_image;
                    if (move_uploaded_file($tmp_product_image, $path) == true) {
                        copy($path, "../" . $path);
                       
                      
                        $query = "INSERT INTO furniture_product (title, category, detail, price, p_size, img, creation_date, stts)
                        VALUES (:title, :category, :detail, :price, :product_size, :product_image, :date_added, :product_status)";
                        $stmt = oci_parse($conn, $query);
                        oci_bind_by_name($stmt, ':title', $title);
                        oci_bind_by_name($stmt, ':category', $category);
                        oci_bind_by_name($stmt, ':detail', $detail);
                        oci_bind_by_name($stmt, ':price', $price);
                        oci_bind_by_name($stmt, ':product_size', $product_size);
                        oci_bind_by_name($stmt, ':product_image', $product_image);
                        oci_bind_by_name($stmt, ':date_added', $formatted_date); 
                        oci_bind_by_name($stmt, ':product_status', $product_status);
                    
                       
                        $result = oci_execute($stmt);
                        if (!$result) {
                            $error = oci_error($stmt);
                            echo "Error executing statement: " . $error['message'];
                            exit;
                        }
                    
                        echo "Data inserted successfully.";
                    
                       
                        oci_free_statement($stmt);
                    } else {
                        echo "Failed to move uploaded file.";
                    }
                }
                ?>


                        <div class="col-lg-3">
                            <input type="text" name="title" class="form-control" placeholder="Product Title">
                        </div>
                        <div class="col-lg-3">
                            <select class="form-control" name="category">
                                <?php
                                
                                $query = "SELECT * FROM categories";
                                $stmt = oci_parse($conn, $query);
                                oci_execute($stmt);
                                while ($row = oci_fetch_assoc($stmt)) {
                                    $category_id = $row['ID'];
                                    $category_name = ucfirst($row['CATEGORY']);
                                    echo "<option value='$category_id'>$category_name</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <input type="text" name="product_size" class="form-control" placeholder="Size">
                        </div>
                        <div class="col-lg-2">
                            <input type="text" name="price" class="form-control" placeholder="Price">
                        </div>
                        <div class="col-lg-2">
                            <select class="form-control" name="product_status">
                                <option value="publish">Publish</option>
                                <option value="draft">Draft</option>
                            </select>
                        </div>
                        <div class="col-lg-12 mt-3">
                            <textarea name="detail" class="form-control" placeholder="Product Detail"></textarea>
                        </div>
                        <div class="col-lg-3 mt-3">
                            <input type="date" name="date_added" class="form-control">
                        </div>
                        <div class="col-lg-9 mt-3">
                            <input type="file" name="upload" class="form-control-file border" >
                        </div>
                        <div class="col-lg-12 mt-3">
                            <input type="submit" name="submit" class="btn btn-primary" value="Add Product">
                        </div><br/>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once('include/footer.php'); ?>
