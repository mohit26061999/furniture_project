<?php 
require_once('include/header.php');

if(!isset($_SESSION['email'])){
    header('location: signin.php');
    exit; 
}

if(isset($_SESSION['email'])){
    $session_id = $_SESSION['id'];
    $session_email = $_SESSION['email'];
    $session_name = $_SESSION['name'];
}

?>

<div class="container-fluid mt-2">
    <script src="ckeditor/ckeditor.js"></script>
    <div class="row">
        <div class="col-md-3 col-lg-3">
            <?php require_once('include/sidebar.php'); ?>
        </div>
        
        <div class="col-md-9 col-lg-9">
            <form method="post" enctype="multipart/form-data">
                <?php
                
                
                if(isset($_GET['pid'])){
                    $Fur_pro_id = $_GET['pid'];

                    $Fur_pr_query = "SELECT * FROM furniture_product WHERE pid=:pid";
                    $Fur_pr_stmt = oci_parse($conn, $Fur_pr_query);
                    oci_bind_by_name($Fur_pr_stmt, ':pid', $Fur_pro_id);
                    oci_execute($Fur_pr_stmt);

                    if($Fur_pr_row = oci_fetch_assoc($Fur_pr_stmt)){
                        $db_pid = $Fur_pr_row['PID'];
                        $db_title = $Fur_pr_row['TITLE'];
                        $db_category = $Fur_pr_row['CATEGORY'];
                        $db_size = $Fur_pr_row['P_SIZE'];
                        $db_price = $Fur_pr_row['PRICE'];    
                        $db_detail = $Fur_pr_row['DETAIL'];
                        $db_image = $Fur_pr_row['IMG'];
                        $db_status = $Fur_pr_row['STTS'];
                    }

                    if(isset($_POST['update'])){ 
                        $title = $_POST['title'];
                        $size = $_POST['p_size'];
                        $price = $_POST['price'];
                        $status = $_POST['status'];
                        $category = $_POST['category'];
                        $detail = $_POST['detail'];
                        $img = $_FILES['upload']['name'];
                        $tmp_image = $_FILES['upload']['tmp_name'];
                        
                        if(!empty($title) || !empty($size) || !empty($price) || !empty($status) || !empty($category) || !empty($detail) || !empty($img)){
                            if($img == ''){
                                $img = $db_image;
                            }
                            
                       
                            $query = "UPDATE furniture_product SET title=:title, category=:category, p_size=:p_size, price=:price, detail=:detail, img=:img, stts=:status WHERE pid=:pid";                            
                            $stmt = oci_parse($conn, $query);
                            
                         
                            oci_bind_by_name($stmt, ':title', $title);
                            oci_bind_by_name($stmt, ':category', $category);
                            oci_bind_by_name($stmt, ':p_size', $size);
                            oci_bind_by_name($stmt, ':price', $price);
                            oci_bind_by_name($stmt, ':detail', $detail);
                            oci_bind_by_name($stmt, ':img', $img);
                            oci_bind_by_name($stmt, ':status', $status);
                            oci_bind_by_name($stmt, ':pid', $Fur_pro_id);
                            
                            if(oci_execute($stmt)){
                                $path = "img/" . $img;
                                if(move_uploaded_file($tmp_image, $path)){
                                    copy($path, "../" . $path);                                                                    
                                }
                                header("location:furniture_pro_edit.php?pid=$Fur_pro_id");
                                exit;
                            }
                        }else {
                            $error = "All Fields are required!";
                        }
                    }
                }
                ?>
                
                <div class="row">
                    <div class="col-md-12">
                        <?php if(isset($error)){
                            echo "<span class='mt-3 mb-4' style='color:red; font-weight:bold;'><i style='color:red; font-weight:bold;' class='fas fa-sad'></i> $error</span>";
                        }?>
                        <div class="form-group">
                            <label for="furniture">Furniture Product Title:</label>
                            <input type="text" class="form-control" name="title" value="<?php echo $db_title;?>" placeholder="Title">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-3">
                        <label for="category">Category:</label>
                        <select class="form-control" name="category">
                            <?php
                            $cat_query = "SELECT * FROM categories ORDER BY id ASC";
                            $cat_stmt = oci_parse($conn, $cat_query);
                            oci_execute($cat_stmt);
                            while($cat_row = oci_fetch_assoc($cat_stmt)){
                                $cat_id = $cat_row['ID'];
                                $cat_name = ucfirst($cat_row['CATEGORY']);
                            ?>
                            <option value='<?php echo $cat_id; ?>' <?php if($cat_id == $db_category){ echo 'selected'; }?> ><?php echo $cat_name;?></option>";
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                   
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="size">Product Size:</label>
                            <input type="text" class="form-control" name="p_size" value="<?php echo $db_size; ?>" placeholder="Size: 25w X 25h">
                        </div>
                    </div>
              
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="size">Product Price:</label>
                            <input type="text" class="form-control" name="price" value="<?php echo $db_price; ?>" placeholder="Price: 25000">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="size">Product Status:</label>
                        <select class="form-control" name="status">
                            <option value="publish" <?php if($db_status=='publish'){ echo 'selected';}?>>Publish</option>
                            <option value="draft" <?php if($db_status=='draft'){ echo 'selected';}?>>Draft</option>
                        </select>
                    </div>
                </div> 
                       
                <div class="row">
                    <div class="col-md-12">
                        <textarea name="detail"><?php echo $db_detail; ?></textarea>
                    </div>
                </div>
                  
                <div class="row mt-3">
                    <div class="col-md-6">      
                        <span>Choose files</span>
                        <input type="file" name="upload" class="form-control-file border" >
                    </div>
                    <div class="col-md-6">
                        <img src="img/<?php echo $db_image;?>" min-width="100%"  height="200px">
                    </div>
                </div>
              
                <input type="submit" name="update" class=" mt-3 btn btn-primary btn-md" value="Update">
                  
            </form>
        </div>
        
    </div>
   
</div>

<?php require_once('include/footer.php'); ?>
