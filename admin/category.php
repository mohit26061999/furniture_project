<?php 
require_once('include/header.php');
if(!isset($_SESSION['email'])){
    header('location: signin.php');
}

?>

<div class="container-fluid mt-2">
    <div class="row">
        <div class="col-md-3 col-lg-3">
            <?php require_once('include/sidebar.php'); ?>
        </div>

        <?php
        if(isset($_GET['del'])) {
            $del = $_GET['del'];
            
            $query = "DELETE FROM categories WHERE id = $del";
            $stmt = oci_parse($conn, $query);
          
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
                        <h1 class="ml-5 display-4 font-weight-normal">View Furniture Categories:</h1>
                    </div>
                </div>
                <hr>
                <form action="" method="post">
                    <div class="row">
                        <?php
                        if(isset($_POST['submit'])) {
                            $category = $_POST['category'];
                            $fontawesome = $_POST['fonts'];
                           
                            $query = "INSERT INTO categories (category, fontawesome_icon) VALUES ('$category', '$fontawesome')";
                            $stmt = oci_parse($conn, $query);
                           
                            oci_execute($stmt);
                        }
                        ?>

                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-lg-6">
                                    <input type="text" name="category" class="form-control" placeholder="Add Category">
                                </div>
                                <div class="col-lg-6">
                                    <input type="text" name="fonts" class="form-control" placeholder="Simply Add fa-example ">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <input type="submit" name="submit" class="btn btn-primary" value="Add cat">
                        </div><br/>
                    </div>
                </form>
                <?php
             
                $query = "SELECT * FROM categories";
                $stmt = oci_parse($conn, $query);
                oci_execute($stmt);

               
                $rows = oci_fetch_all($stmt, $results, null, null, OCI_FETCHSTATEMENT_BY_ROW);
                if ($rows > 0) {
                    ?>
                    <div class="row mt-5">  
                        <div class="col-md-12 col-lg-12">
                            <table class="table table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Font Awesome icon</th>
                                        <th>Categories</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                
                                    foreach ($results as $row) {
                                        $id = isset($row['ID']) ? $row['ID'] : '';
                                        $font_awesome = isset($row['FONTAWESOME_ICON']) ? $row['FONTAWESOME_ICON'] : '';
                                        $category = isset($row['CATEGORY']) ? ucfirst($row['CATEGORY']) : '';
                                       
                                        echo "<tr>";
                                        echo "<td>$id</td>";
                                        echo "<td><i class='text-primary fad $font_awesome'></i></td>";
                                        echo "<td>$category</td>";
                                        echo "<td class='text-center'>";
                                        echo "<a href='editcat.php?edit=$id' class='btn btn-primary'>Edit</a>";
                                        echo "<a href='category.php?del=$id' class='btn btn-danger ml-2' onclick='return confirm(\"Are you sure you want to delete this category?\")'>Delete</a>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php
                } else {
                    echo "<div class='row mt-5'><div class='col-md-12'><p>No categories found</p></div></div>";
                }
                ?>
            </div>
        </div>
    </div>
</div>
<?php 
  oci_close($conn);
  ?>

<?php require_once('include/footer.php'); ?>
