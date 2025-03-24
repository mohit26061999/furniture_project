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
      
        $query = "SELECT message_id, email, message_text FROM messages";
        $stmt = oci_parse($conn, $query);
        oci_execute($stmt);
        ?>
        <table border="1">
        <div class="row">
                <div class="col-md-1">
                    <i class="fas fa-envelope fa-6x text-primary"></i>
                </div>
                <div class="col-md-11 text-left mt-4">
                    <h1 class="ml-5 display-4 font-weight-normal">View All Contact Us Messages:</h1>
                </div>
            </div>
            <tr>
                <th>Message ID</th>
                <th>Email ID</th>
                <th>Message Content</th>
            </tr>
            <?php
            while ($row = oci_fetch_assoc($stmt)) {
                echo "<tr>";
                echo "<td>" . $row['MESSAGE_ID'] . "</td>";
                echo "<td>" . $row['EMAIL'] . "</td>";
                echo "<td>" . oci_result($stmt, 'MESSAGE_TEXT') . "</td>";
                echo "</tr>";
            }
            ?>
        </table>
        <?php
        oci_free_statement($stmt);
        ?>
    </div>

</div>
<?php 
  oci_close($conn);
  ?>

<?php include 'include/footer.php'; ?>
