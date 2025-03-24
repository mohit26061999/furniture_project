<?php include 'index.php'; ?>
<style>
.content {
    display: flex;
    justify-content:right; 
    margin-top: -245px;
}

.table-container {
    width: 70%; 
    background-color: #f4f4f4;
    border-radius: 10px; 
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 20px; 
    border: 1px solid #ddd; 
}


.table-container table {
    width: 100%; 
    border-collapse: collapse; 
}

.table-container th, .table-container td {
    padding: 8px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}


.table-container tr:nth-child(even) {
    background-color: #f9f9f9; 
}


</style>
<div class="content">
    <div class="table-container">
        <?php
        
        $query = "SELECT customer_id,rating,product_id,coment,created_at FROM product_reviews";
        $stmt = oci_parse($conn, $query);
        oci_execute($stmt);
        ?>
        <table border="1">
        <div class="row">
                <div class="col-md-1">
                    <i class="fas fa-comment fa-6x text-primary"></i>
                </div>
                <div class="col-md-11 text-left mt-4">
                    <h1 class="ml-5 display-4 font-weight-normal">View All Feedbacks:</h1>
                </div>
            </div>
            <tr>
                <th>customer_id ID</th>
                <th>Rating</th>
                <th>Product_id</th>
                <th>Comment</th>
                <th>Time Of Message</th>

            </tr>
            <?php
            while ($row = oci_fetch_assoc($stmt)) {
                echo "<tr>";
                echo "<td>" . $row['CUSTOMER_ID'] . "</td>";
                echo "<td>" . $row['RATING'] . "</td>";
                echo "<td>" . $row['PRODUCT_ID'] . "</td>";
                echo "<td>" . $row['COMENT'] . "</td>";
                echo "<td>" . $row[ 'CREATED_AT'] . "</td>" ;
                echo "</tr>";
            }
            ?>
        </table>
        <?php
        oci_free_statement($stmt);
        ?>
    </div>

    
    <?php
    
    if (isset($_SESSION['email'])) {
        
        ?>
       
        <?php
    } else {
       
        echo "<p>Please login to leave a review.</p>";
    }
    ?>
</div>
<?php 
  oci_close($conn);
  ?>
<?php include 'include/footer.php'; ?>