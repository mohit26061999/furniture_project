<?php
$host = "localhost";
$username = "sys";
$password = "mohit";
$sid = "XE"; 


$conn = oci_connect($username, $password, "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=$host)(PORT=1521))(CONNECT_DATA=(SERVICE_NAME=$sid)))", "", OCI_SYSDBA);

if (!$conn) {
    $error = oci_error();
    echo "Failed to connect to Oracle: " . $error['message'];
} else {
    echo "Connected to Oracle successfully!";
 

    
}
?>