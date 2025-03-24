<?php include('include/header.php'); ?>

<div class="jumbotron">
    <h1 class="text-center mt-5">Contact us</h1>
</div>

<div class="container mt-3">
    <div class="row">
        <div class="col-md-6">
            <h3>Our Office</h3>
            <hr>
            <p>Punjabi University Patiala</p>
        </div>
        <div class="col-md-6">
            <form action="" method="post" class="p-3">
                <div class="form-group">
                    <input type="text" name="fullname" placeholder="Full Name" class="form-control">
                </div>
                     
                <div class="form-group">
                    <input type="text" name="email" placeholder="Email" class="form-control">
                </div>
                     
                <div class="form-group">
                    <textarea class="form-control" name="message" rows="5" cols="20" placeholder="Message"></textarea>
                </div>

                <div class="form-group text-center mt-4">
                    <input type="submit" name="submit" class="btn btn-primary" value="Send">
                </div>
            </form>
        </div>
    </div>
          
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3519.965446408993!2d-75.41314618453484!3d40.53781807944382!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c6bfb0c0f0af91%3A0x3fa09756d452f5b8!2sPunjabi%20University%2C%20Patiala%2C%20Punjab%20147501%2C%20India!5e0!3m2!1sen!2s!4v1648826415881!5m2!1sen!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>

</div>
         
<?php include('include/footer.php');?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $message = $_POST['message'];

   
    $query = "INSERT INTO messages (fullname, email, message_text) VALUES (:fullname, :email, :message)";
    $stmt = oci_parse($conn, $query);
    oci_bind_by_name($stmt, ':fullname', $fullname);
    oci_bind_by_name($stmt, ':email', $email);
    oci_bind_by_name($stmt, ':message', $message);
    oci_execute($stmt);
    

    echo "<script>alert('Your message has been sent successfully. We will get back to you soon.');</script>";
}
?>
 <?php 
  oci_close($conn);
  ?>
