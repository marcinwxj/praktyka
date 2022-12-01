<?php

$conn = mysqli_connect("localhost","root","","MyEvento");

if($conn == TRUE) {
    echo "jest polaczenie";
    } else {
        echo "nie ma polaczenia";
    }



  
?>



