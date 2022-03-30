<?php
    if(!isset($_POST["name"])){
        header("Location: ./Error");
    }

    $name = $_POST['name'];
    
    session_start();
    $_SESSION["key"] = openssl_encrypt($name, 'AES-128-ECB', hash("md4", "geyamaclub")); 
    
    header("Location: ./". $name);
?>