<?php //basically following tutorial
    $con = mysqli_connect("localhost","root","","pretend_photo_website"); 
    if (mysqli_connect_errno()){
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
?>