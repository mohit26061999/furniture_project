<?php
include('include/header.php');

if(!isset($_SESSION['id'])){
    header('location:sign-in.php');
}

if (isset($_POST['submit'])) {
    $invoice_no = $_POST['invoice_no'];
    $complaint = $_POST['complaint'];
    $complaint_timestamp = date('d-M-Y h:i:s A');
    $customer_id = $_SESSION['id']; 

    $query = "INSERT INTO complaint (customer_id, c_name, email, invoice_no, complaint, complaint_timestamp) 
              VALUES (:customer_id, :c_name, :email, :invoice_no, :complaint, :complaint_timestamp)"; // Changed column name
    $stmt = oci_parse($conn, $query);
    oci_bind_by_name($stmt, ':customer_id', $customer_id);
    oci_bind_by_name($stmt, ':c_name', $_SESSION['name']);
    oci_bind_by_name($stmt, ':email', $_SESSION['email']);
    oci_bind_by_name($stmt, ':invoice_no', $invoice_no);
    oci_bind_by_name($stmt, ':complaint', $complaint);
    oci_bind_by_name($stmt, ':complaint_timestamp', $complaint_timestamp); 
    
    if (oci_execute($stmt)) {
        echo "Complaint submitted successfully!";
        
        header("Location: index.php");
        exit;
    } else {
        $error_message = oci_error($stmt); 
        echo "Error: Unable to submit complaint. " . $error_message['message'];
    }
}

$invoice_query = "SELECT invoice_no FROM customer_order WHERE customer_id = :customer_id";
$invoice_stmt = oci_parse($conn, $invoice_query);
oci_bind_by_name($invoice_stmt, ':customer_id', $_SESSION['id']);
oci_execute($invoice_stmt);

$customer_query = "SELECT cust_name, cust_email FROM customer WHERE cust_id = :customer_id";
$customer_stmt = oci_parse($conn, $customer_query);
oci_bind_by_name($customer_stmt, ':customer_id', $_SESSION['id']);
oci_execute($customer_stmt);
$customer_row = oci_fetch_assoc($customer_stmt);
$customer_name = $customer_row['CUST_NAME'];
$customer_email = $customer_row['CUST_EMAIL'];
?>

<div class="container">
    <h2>Submit Complaint</h2>
    <form method="POST">
        <label for="name">Name:</label>
        <input type="text" id="name" name="c_name" value="<?php echo $customer_name; ?>" required><br><br>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $customer_email; ?>" required><br><br>
        
        <label for="invoice_no">Select Invoice Number:</label>
        <select id="invoice_no" name="invoice_no" required>
            <?php 
            while ($row = oci_fetch_assoc($invoice_stmt)) {
                echo "<option value='" . $row['INVOICE_NO'] . "'>" . $row['INVOICE_NO'] . "</option>";
            }
            ?>
        </select><br><br>
        
        <label for="complaint">Complaint:</label><br>
        <textarea id="complaint" name="complaint" rows="4" required></textarea><br><br>
        
        <button type="submit" name="submit">Submit</button>
    </form>
</div>




<style>
   
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 800px;
        margin: auto;
        padding: 20px;
    }

    h2 {
        margin-top: 40px;
        text-align: center;
    }

    form {
        margin-top: 20px;
        text-align: center;
    }

    label {
        display: block;
        margin-bottom: 5px;
        text-align: left;
    }

    input[type="text"],
    input[type="email"],
    textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
    }

    textarea {
        resize: vertical;
    }

    button[type="submit"] {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    button[type="submit"]:hover {
        background-color: #45a049;
    }

   
    @media (max-width: 768px) {
        .container {
            padding: 10px;
        }
    }

</style>
<?php 
  oci_close($conn);
  ?>

<?php include('include/footer.php'); ?>
