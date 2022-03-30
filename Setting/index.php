<?php
    require_once "../decrypt.php";
    require_once "../log/add_log.php";

    if(key_check(basename(dirname(__FILE__)))){
        $log_file = "../log/log.txt";
        $log_list = file($log_file);
        $log_list = array_reverse($log_list);
    }else{
        add_log("main", "settingページのキー認証に失敗しました");

        header("Location: ../Error");
    }

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GeyamaClub | Practice</title>
    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="w-full h-full bg-gray-200" style="overflow: auto;">
    <!--nobanner-->
    <div class="flex p-2 m-5 rounded-lg">
        <!--
        <div class="flex-1 text-center p-1 bg-white rounded-lg mx-1 cursor-pointer" id="setting_btn">
            <h1 class="text-xl font-bold">設定</h1>
        </div>
        -->
        <div class="flex-1 text-center p-1 bg-white rounded-lg mx-1 cursor-pointer" id="log_btn">
            <h1 class="text-xl font-bold">ログ</h1>
        </div>
        <!--
        <div class="flex-1 text-center p-1 bg-white rounded-lg mx-1 cursor-pointer" id="backup_btn">
            <h1 class="text-xl font-bold">バックアップ</h1>
        </div>
        -->
    </div>
    <!--
    <div class="rounded-lg" id="setting_screen">
        <div class="md:flex mb-6 bg-white p-3 m-5 rounded-lg">
            <div class="md:w-1/3">
                <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4 py-4">
                    ログインパスワード
                </label>
            </div>
            <div class="md:w-2/3">
                <input class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500 my-2" id="new_password" type="password" placeholder="新しいパスワード" value="">
                <input class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500 my-2" id="new_password_check" type="password" placeholder="確認">
                <div class="flex w-full justify-end">
                    <input type="button" class="shadow bg-blue-500 hover:bg-blue-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-3 cursor-pointer" value="更新" id="password_btn">
                </div>
            </div>
        </div>
        <div class="md:flex mb-6 bg-white p-3 m-5 rounded-lg">
            <div class="md:w-1/3">
                <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4 py-4">
                    参加費
                </label>
            </div>
            <div class="md:w-2/3">
                <input class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500 my-2" id="new_fee" type="number" placeholder="新しい参加費" value="">
                <div class="flex w-full justify-end">
                    <input type="button" class="shadow bg-blue-500 hover:bg-blue-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-3 cursor-pointer" value="更新" id="fee_btn">
                </div>
            </div>
        </div>
        <div class="md:flex mb-6 bg-white p-3 m-5 rounded-lg">
            <div class="md:w-1/3">
                <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4 py-4">
                    体育館代
                </label>
            </div>
            <div class="md:w-2/3">
                <input class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500 my-2" id="new_gym" type="number" placeholder="新しい体育館代" value="">
                <div class="flex w-full justify-end">
                    <input type="button" class="shadow bg-blue-500 hover:bg-blue-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-3 cursor-pointer" value="更新" id="gym_btn">
                </div>
            </div>  
        </div>
    </div>
    -->

    <div class="p-3 bg-white m-8 mt-4 rounded-lg" id="log_screen">
        <?php foreach ($log_list as $log){?>
            <h1 class="text-lg"><?php echo $log?></h1>
        <?php }?>    
    </div>


    <div class="p-3 bg-white m-5 rounded-lg h-full" id="backup_screen">

    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@riversun/sortable-table/lib/sortable-table.js"></script>
    <script src="https://unpkg.com/chartjs-plugin-colorschemes"></script>
    <script></script>
    <script type="text/javascript" src="./index.js"></script>
</body>
</html>