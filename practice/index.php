<?php
    require "../config.php";
    require "../decrypt.php";
    require "../log/add_log.php";

    if(key_check(basename(dirname(__FILE__)))){
        $today = date("Y-m-d H:i:s");
    
        try{
            $plan_list = ORM::for_table('plan')->find_many();
            $plan_date = array();
            
            foreach($plan_list as $plan){
                array_push($plan_date, $plan["date"]);
            }
    
            $plan_date = json_encode($plan_date);
    
            $max_id = ORM::for_table('payments')->max('id'); 
            $balance = ORM::for_table('payments')->where(array("id" => $max_id))->find_one();
            $balance = $balance["balance"];
            $all_member_number = count(ORM::for_table('member')->find_result_set());       
        }catch(Exception $e){
            add_log("error", "データベース接続エラー || " . $e);
        }
    }else{
        add_log("main", "practiceページのキー認証に失敗しました");
        header("Location: ../Error");
    }


?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <?php include "../layout/head.html";?>
</head>
<body class="w-full h-full bg-gray-200" style="overflow-y: scroll;">
    <!--nobanner-->
    <div class="flex w-full">
        <div class="w-16 h-16 rounded-full m-2 pt-2 pl-1 bg-white cursor-pointer" id="back_date">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                <path d="M3.86 8.753l5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
            </svg>
        </div>
        <div class="flex-1 h-16 bg-white p-3 text-center rounded-full m-2">
            <h1 class="font-bold text-xs lg:text-3xl" id="practice_date"></h1>
        </div>
        <div class="w-16 h-16 rounded-full m-2 pt-2 pl-1 bg-white cursor-pointer" id="next_date">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-caret-right-fill" viewBox="0 0 16 16">
                <path d="M12.14 8.753l-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"/>
            </svg>
        </div>
    </div>
    <div class="w-full flex flex-wrap rounded-lg">
        <div class="flex-1 h-48 p-2 bg-white m-5 flex" id="number_participants">
            <div class="w-1/5 lg:m-6 m-3 mt-8">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-people w-full text-green-400" viewBox="0 0 16 16">
                    <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0zM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816zM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275zM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"/>
                </svg>
            </div>
            <div class="mt-8">
                <h1 class="text-gray-600">参加人数</h1>
                <h1 class="text-5xl font-bold m-1" id="participants_number"></h1>
                <h1 class="text-2xl m-1" id="participants_number_parcent"></h1>
            </div>
        </div>
        <div class="flex-1 h-48 p-2 bg-white m-5 rounded-lg flex" id="income_parent">
            <div class="w-1/5 lg:m-6 m-3 mt-8">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-coin w-full text-purple-400" viewBox="0 0 16 16">
                    <path d="M5.5 9.511c.076.954.83 1.697 2.182 1.785V12h.6v-.709c1.4-.098 2.218-.846 2.218-1.932 0-.987-.626-1.496-1.745-1.76l-.473-.112V5.57c.6.068.982.396 1.074.85h1.052c-.076-.919-.864-1.638-2.126-1.716V4h-.6v.719c-1.195.117-2.01.836-2.01 1.853 0 .9.606 1.472 1.613 1.707l.397.098v2.034c-.615-.093-1.022-.43-1.114-.9H5.5zm2.177-2.166c-.59-.137-.91-.416-.91-.836 0-.47.345-.822.915-.925v1.76h-.005zm.692 1.193c.717.166 1.048.435 1.048.91 0 .542-.412.914-1.135.982V8.518l.087.02z"/>
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                    <path d="M8 13.5a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11zm0 .5A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-gray-600 mt-8">練習収益</h1>
                <div class="w-full flex">
                    <div class="flex-1 lg:block hidden">
                        <h1 class="text-2xl m-2 text-green-400" id="income"></h1>
                        <h1 class="text-2xl m-2 text-red-400" id="spending"></h1>
                    </div>
                    <div class="flex-1">
                    <h1 class="text-5xl m-3 font-bold" id="practice_balance"></h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex-1 h-48 p-2 bg-white m-5 rounded-lg flex" id="balance_parent">
            <div class="w-1/5 lg:m-6 m-0 mt-8">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-currency-dollar w-full text-indigo-400" viewBox="0 0 16 16">
                    <path d="M4 10.781c.148 1.667 1.513 2.85 3.591 3.003V15h1.043v-1.216c2.27-.179 3.678-1.438 3.678-3.3 0-1.59-.947-2.51-2.956-3.028l-.722-.187V3.467c1.122.11 1.879.714 2.07 1.616h1.47c-.166-1.6-1.54-2.748-3.54-2.875V1H7.591v1.233c-1.939.23-3.27 1.472-3.27 3.156 0 1.454.966 2.483 2.661 2.917l.61.162v4.031c-1.149-.17-1.94-.8-2.131-1.718H4zm3.391-3.836c-1.043-.263-1.6-.825-1.6-1.616 0-.944.704-1.641 1.8-1.828v3.495l-.2-.05zm1.591 1.872c1.287.323 1.852.859 1.852 1.769 0 1.097-.826 1.828-2.2 1.939V8.73l.348.086z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-gray-600 mt-8">全体収益（これは現在の収益です）</h1>
                <h1 class="text-5xl font-bold m-3" id="balance"></h1>
            </div>
        </div>
    </div>
    <div class="w-full lg:flex flex-wrap">
        <div class="flex-1 h-60 p-2 bg-white m-5 rounded-lg">
            <h1 class="text-gray-700 m-2">コントローラー</h1>
            <div class="flex w-full m-2">
                <div class="flex-1 m-1">
                    <h1 class="text-xl mx-1 font-bold">募集状況</h1>
                </div>
                <div class="inline-block relative w-full flex-1 m-1">
                    <select id="status_select" class="block appearance-none w-full bg-white border hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                        <option value="0">未募集</option>
                        <option value="1">募集中</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>
            </div>
            <div class="w-full m-2">
                <h1 class="text-xl m-1 font-bold">収入</h1>
            </div>
            <div class="lg:flex w-full m-2">
                <div class="flex-1">
                    <h1 class="text-xl m-1">参加費</h1>
                </div>
                <div class="flex-1 m-1">
                    <input class="bg-gray-200 w-full lg:w-64 text-gray-700 border border-gray-200 rounded-full py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 income_obj control" id="participation_fee" type="number">
                    <nobr class="text-xl m-1">円</nobr>
                </div>
            </div>
            <div class="lg:flex w-full m-2">
                <div class="flex-1">
                    <h1 class="text-xl m-1">その他</h1>
                </div>
                <div class="flex-1 m-1">
                    <input class="bg-gray-200 w-full lg:w-64 text-gray-700 border border-gray-200 rounded-full py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 income_obj control" id="income_other" type="number">
                    <nobr class="text-xl m-1">円</nobr>
                    <input class="bg-gray-200 w-full lg:w-64 text-gray-700 border border-gray-200 rounded-full py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 income_obj my-2 w-64 lg:w-full control" id="income_other_memo" type="text" placeholder="メモ">
                </div>
            </div>
            <div class="w-full m-2">
                <div class="flex-1">
                    <h1 class="text-xl m-1 font-bold">支出</h1>
                </div>
            </div>
            <div class="lg:flex w-full m-2">
                <div class="flex-1">
                    <h1 class="text-xl m-1">体育館代</h1>
                </div>
                <div class="flex-1 m-1">
                    <input class="bg-gray-200 w-full lg:w-64 text-gray-700 border border-gray-200 rounded-full py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 spending_obj control" id="gym_input" type="number">
                    <nobr class="text-xl m-1">円</nobr>
                </div>
            </div>
            <div class="lg:flex w-full m-2">
                <div class="flex-1">
                    <h1 class="text-xl m-1">シャトル代</h1>
                </div>
                <div class="flex-1 m-1">
                    <input class="bg-gray-200 w-full lg:w-64 text-gray-700 border border-gray-200 rounded-full py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 spending_obj control" id="shattle_input" type="number">
                    <nobr class="text-xl m-1">円</nobr>
                </div>
            </div>
            <div class="lg:flex w-full m-2">
                <div class="flex-1">
                    <h1 class="text-xl m-1">その他</h1>
                </div>
                <div class="flex-1 m-1">
                    <input class="bg-gray-200 w-full lg:w-64 text-gray-700 border border-gray-200 rounded-full py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 spending_obj control" id="other_input" type="number">
                    <nobr class="text-xl m-1">円</nobr>
                    <input class="bg-gray-200 w-full lg:w-64 text-gray-700 border border-gray-200 rounded-full py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 income_obj my-2 w-64 lg:w-full control" id="other_memo" type="text" placeholder="メモ">
                </div>
            </div>
            <div class="lg:flex w-full m-2">
                <div class="flex-1 lg:block hidden">
                    <h1 class="text-xl m-1 font-bold">決済</h1>
                </div>
                <div class="flex-1">
                    <button class="bg-blue-700 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full w-full" id="settlement_btn">
                        決定
                    </button>
                    <h1 class="text-xl m-1 font-bold" id="settlement_id"></h1>
                </div>
                <div class="">
                    <h1 class="text-xl m-1 font-bold" id="settlement_memo"></h1>
                </div>
            </div>
        </div>
        <div class="flex-1 h-60 p-2 bg-white m-5 rounded-lg">
            <h1 class="text-gray-700">メモ</h1>
            <textarea class="h-64 w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 mt-2 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="memo" type="text" placeholder="メモは入力されていません"></textarea>
            <div class="text-right">
                <button class="bg-blue-700 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full" id="save_memo">保存</button>
            </div>
        </div>
    </div>
    <div class="p-2 bg-white m-5 rounded-lg">
        <h1 class="text-gray-700">参加者リスト</h1>
        <div class="m-3">
            <input class="bg-gray-200 text-gray-700 border border-gray-200 rounded-full py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 income_obj my-2 w-64 control" id="add_member_name" type="text" list="name_list" placeholder="メンバー追加">
            <datalist id="name_list"></datalist>
            <button class="bg-blue-700 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full" id="add_member_btn">追加</button>
            <button class="bg-red-700 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-full" id="delete_member_btn">削除</button>
        </div>
        <table class="table-fixed w-full text-center">
            <thead>
                <tr>
                <th class="px-4 py-2">ID</th>
                <th class="px-4 py-2">氏名</th>
                <th class="px-4 py-2 lg:table-cell hidden">参加回数</th>
                <th class="px-4 py-2 lg:table-cell hidden">開催数</th>
                <th class="px-4 py-2 lg:table-cell hidden">参加率</th>
                <th class="px-4 py-2 lg:table-cell hidden">最終参加日</th>
                <th class="px-4 py-2 lg:table-cell hidden">加入日</th>
                <th class="px-4 py-2 lg:table-cell hidden">メモ</th>
                </tr>
            </thead>
        </table>
        <table class="table-fixed w-full text-center" id="participant_list">
            <tbody>

            </tbody>
        </table>
    </div>
    <?php include "../layout/script.html";?>
    <script>
        plan_date = <?php echo $plan_date?>;
        balance = <?php echo $balance?>;
        all_member_number = <?php echo $all_member_number?>;
    </script>
    <script type="text/javascript" src="./index.js"></script>
</body>
</html>