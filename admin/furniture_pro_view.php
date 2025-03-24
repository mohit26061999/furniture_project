<?php
include("include/header.php");

if (!isset($_SESSION['email'])) {
    header('location: signin.php');
    exit; 
}

if (isset($_GET['del'])) {
    $del = $_GET['del'];

    
    $del = filter_var($del, FILTER_VALIDATE_INT);

    if ($del === false || $del <= 0) {
        echo "<script>alert('Invalid product ID');</script>";
    } else {
       
        $dependent_query = "SELECT COUNT(*) AS total_dependent_records FROM (
                                SELECT product_id FROM cart WHERE product_id = :del
                                UNION ALL
                                SELECT product_id FROM customer_order WHERE product_id = :del
                            )";

        $stmt = oci_parse($conn, $dependent_query);
        oci_bind_by_name($stmt, ':del', $del);
        oci_execute($stmt);

        $dependent_row = oci_fetch_assoc($stmt);
        $total_dependent_records = $dependent_row['TOTAL_DEPENDENT_RECORDS'];

        if ($total_dependent_records > 0) {
            echo "<script>alert('Cannot delete product. Dependent records found.');</script>";
        } else {
            $query = "DELETE FROM furniture_product WHERE pid = :del";
            $stmt = oci_parse($conn, $query);
            oci_bind_by_name($stmt, ':del', $del);

            if (oci_execute($stmt)) {
                echo "<script>alert('Product deleted successfully');</script>";
            } else {
                echo "<script>alert('Failed to delete product');</script>";
            }
        }
    }
}

if (isset($_GET['status'])) {
    $status = $_GET['status'];
}
?>



<div class="container-fluid mt-2">
    <div class="row">
        <div class="col-md-3">
            <?php include("include/sidebar.php");?>
        </div>

        <div class="col-md-9">

            <div class="row">
                <div class="col-md-1">
                    <i class="fad fa-th-list fa-6x text-primary"></i>
                </div> 

                <div class="col-md-7">
                    <h2 class="display-4 ml-2 mt-4">View Furniture Products:</h2>
                </div> 
                <div class="col-md-4">
                    <div class="font-weight-bold mt-5 text-right" style="font-size:24px;">
                        <label>Sort: </label> 
                        <a href="furniture_pro_view.php?status=publish">Publish</a> | <a href="furniture_pro_view.php?status=draft">Draft</a>
                    </div>
                </div> 
            </div>
            <hr>
            <table class="table table-responsive table-hover ">
                <thead class="thead-light">
                    <tr>
                        <th>Product Id</th>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Size</th>
                        <th>Price (₹)</th>
                        <th>Detail</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th colspan="4">Actions(Edit/Del)</th>
                        <th colspan="4"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $pr_query = "SELECT * FROM furniture_product fp INNER JOIN categories cat ON fp.category = cat.id";
                    if(isset($status)){
                        $pr_query .= " WHERE stts = :status";
                    }
                    $pr_query .= " ORDER BY pid";
                    $stmt = oci_parse($conn, $pr_query);
                    if(isset($status)){
                        oci_bind_by_name($stmt, ':status', $status);
                    }
                    oci_execute($stmt);
                    while($pr_row = oci_fetch_assoc($stmt)){
                        $pid = $pr_row['PID'];
                        $title = $pr_row['TITLE'];
                        $category = $pr_row['CATEGORY'];
                        $size = $pr_row['P_SIZE'];
                        $price = $pr_row['PRICE'];    
                        $detail = $pr_row['DETAIL'];
                        $image = $pr_row['IMG'];
                        $status = $pr_row['STTS'];
                        $date = $pr_row['CREATION_DATE'];
                    ?>
                    <tr>
                        <td><?php echo $pid;?></td>
                        <td width="120px"><img src="img/<?php echo $image;?>" width="100%"></td>
                        <td width="150px"><?php echo $title;?></td>
                        <td><?php echo $category;?></td>
                        <td><?php echo $size;?></td>
                        <td><?php echo $price;?></td>
                        <td><?php echo $detail;?></td>
                        <td><?php echo $status;?></td>
                        <td><?php echo $date;?></td>
                        <td colspan="20" class="text-center">
                            <a title="Edit Product" href="furniture_pro_edit.php?pid=<?php echo $pid;?>" class="btn btn-primary btn-sm"><i class="fal fa-edit"></i></a>
                            <a title="Delete Product" href="furniture_pro_view.php?del=<?php echo $pid;?>" class="btn btn-danger btn-sm">X</a>
                        </td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>

        </div>
    </div>
</div>

<?php include("include/footer.php"); ?>
