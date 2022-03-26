<?php 
    require_once "../../log/add_log.php";

    session_start();

    $ip = $_SERVER["REMOTE_ADDR"];

    if(!isset($_POST['password'])){
        header("Location: ../");
    }else{
        $password = $_POST['password'];

        if(password_verify($password, '$2y$10$PMuup4WteowqaUIkaDNhKejBmp5zZ6BSzDLGk6q.RV4tyh2Ss2lbS')){
            $_SESSION["login_flag"] = true;

            add_log("Debug", $ip, "ログインしました");
            header("Location: ../../#practice");
        }else{

            add_log("Debug", $ip, "ログインに失敗しました");
            header("Location: ../");
        }
    }    
?>