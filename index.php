<?php
    require_once "./config.php";
    require_once "./idiorm.php";

    ini_set('display_errors', 0);

    session_start();

    $ip = $_SERVER["REMOTE_ADDR"];

    if(!isset($_POST['password'])){
        if(isset($_SESSION['pass'])){
            if(hash("md4", "geyamaclub") != $_SESSION['pass']){
                header("Location: ./Login");
            }
        }else{
            header("Location: ./Login");
        }
    }else{
        $password = $_POST['password'];

        if(password_verify($password, '$2y$10$PMuup4WteowqaUIkaDNhKejBmp5zZ6BSzDLGk6q.RV4tyh2Ss2lbS')){
            $now = date("Y-m-d H:i:s");
            $log = "[". $now. "] (main)". " : 「". $ip. "」がログインしました。". "\n"; 
            file_put_contents("./log/main.txt", $log, FILE_APPEND);

            $_SESSION["pass"] = hash("md4",$password);
        }else{
            $now = date("Y-m-d H:i:s");
            $log = "[". $now. "] (main)". " : 「". $ip. "」がログインに失敗しました。(". $password .")". "\n"; 
            file_put_contents("./log/main.txt", $log, FILE_APPEND);

            header("Location: ./Login");
        }
    }    
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <?php include "./layout/head.html"?>
    <link rel="stylesheet" href="./index.css">
</head>
<body class="w-screen h-screen m-0 overflow-visible">
    <!--nobanner-->
    <main class="h-full">
        <div class="flex w-full h-full">
            <div class="h-full text-left p-2 border-r-4 border-gray-300 menu animation_reverse absolute lg:static bg-gray-800 text-white" id="menu">
                <div class="mx-auto w-full flex">
                    <h1 class="text-2xl font-bold hide_object ml-2 my-4">GeyamaClub</h1>
                    <div class="text-right w-full mr-1" style="height: 44px;">
                        <input type="checkbox" id="menu-btn-check" checked>
                        <label for="menu-btn-check" class="menu-trigger">
                            <span></span>
                            <span></span>
                            <span></span>
                        </label>
                    </div>
                </div>    
                <div class="flex hover:bg-gray-600 cursor-pointer rounded-full m-3 p-2" id="practice">
                     <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-pencil-fill w-6" viewBox="0 0 16 16" id="practice_icon">
                        <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
                    </svg>
                    <h1 class="text-2xl m-2 font-bold hide_object">Practice</h1>
                </div>
                <div class="flex hover:bg-gray-600 cursor-pointer rounded-full m-3 p-2" id="payments">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-cash-stack w-6" viewBox="0 0 16 16">
                        <path d="M1 3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1H1zm7 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/>
                        <path d="M0 5a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V5zm3 0a2 2 0 0 1-2 2v4a2 2 0 0 1 2 2h10a2 2 0 0 1 2-2V7a2 2 0 0 1-2-2H3z"/>
                    </svg>
                    <h1 class="text-2xl m-2 font-bold hide_object">Payments</h1>
                </div>
                <div class="flex hover:bg-gray-600 cursor-pointer rounded-full  m-3 p-2" id="members">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-person-fill w-6" viewBox="0 0 16 16">
                        <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                    </svg>
                    <h1 class="text-2xl m-2 font-bold hide_object">Members</h1>
                </div>
                <div class="flex hover:bg-gray-600 cursor-pointer rounded-full m-3 p-2" id="plan">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-calendar w-6" viewBox="0 0 16 16">
                        <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                    </svg>
                    <h1 class="text-2xl m-2 font-bold hide_object">Plan</h1>
                </div>
                <div class="flex hover:bg-gray-600 cursor-pointer rounded-full m-3 p-2" id="setting">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-gear-fill w-6" viewBox="0 0 16 16">
                        <path d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872l-.1-.34zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z"/>
                    </svg>
                    <h1 class="text-2xl m-2 font-bold hide_object">Setting</h1>
                </div>
            </div>
            <div class="md:flex-auto bg-gray-200 ml-20 lg:ml-0">
                <div class="flex justify-center bg-white py-2" id="ad">
                    <script type="text/javascript" src="https://cache1.value-domain.com/xa.j?site=geyamaclub.s203.xrea.com"></script>
                </div>
                <iframe src="./Practice/" frameborder="0" class="w-full overflow-scroll" style="height: 90%;"  name="content_frame" id="content_frame"></iframe>
            </div>
        </div>
    </div>
    </main>
    <script src="https://cdn.tailwindcss.com"></script>
    <script type="text/javascript" src="./index.js"></script>
</body>
</html>