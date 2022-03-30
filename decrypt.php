<?php
    function key_check($page){
        session_start();

        if(!isset($_SESSION['key']) || !isset($_SESSION['pass'])){
            return false;
        }else{
            $key = $_SESSION['key'];
            unset($_SESSION["key"]);

            $pass = $_SESSION['pass'];

            $request_page = openssl_decrypt($key, 'AES-128-ECB', $pass);

            if($page == $request_page){
                return true;
            }else{
                return false;
            }
        }
    }
?>