<?php
    class Home{
        private $password;

        public function __construct($password){
            $this->password = $password;
        }

        public function login(){
            $user_pass = "";
            if(isset($_POST['password'])){
                $user_pass = $_POST['password'];
            }
        
            if(isset($_SESSION["password"])){
                $user_pass = $_SESSION["password"];
            }
        
            if(password_verify($user_pass, $this->password)){
                $_SESSION["password"] = $user_pass;

                return true;
            }

            return false;
        }
    }
?>