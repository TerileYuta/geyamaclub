<?php
    require_once "../config.php";
    require_once "../idiorm.php";
    require_once "../decrypt.php";

    if(key_check(basename(dirname(__FILE__)))){
        try{
            $payments_list = ORM::for_table('payments')->order_by_desc("id")->find_many();
    
            $latest_id = $payments_list[0]["id"];
            $payments = array();
            $date = array();
            foreach($payments_list as $payment){
                $list = array(
                    "name" => $payment['name'],
                    "balance" => $payment['balance'],
                    "action" => $payment['action'],
                    "type" => $payment['type'],
                    "practice_id" => $payment['practice_id'],
                    "update_date" => $payment['update_date']
                );
    
                $id = $payment['id'];
                $month = date("Y-m", strtotime($payment["update_date"]));
    
                if(isset($date[$month])){
                    $date_list = $date[$month];
                }else{
                    $date_list = array();
                }
    
                array_push($date_list, $id);
                $date[$month] = $date_list;
    
                $payments[$id] = $list;
            }
    
            $payments = json_encode($payments);
            $date = json_encode($date);
        }catch(Exception $e){
            add_log("error", "データベース接続エラー || ". $e);
            header("Location: ../Error");
        }    
    }else{
        add_log("main", "paymentsページのキー認証に失敗しました");

        header("Location: ../Error");
    }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <?php include "../layout/head.html";?>
</head>
<body class="w-full h-full bg-gray-200" style="overflow: scroll;">
    <!--nobanner-->
    <div class="lg:flex p-2 lg:m-5 m-2 rounded-lg h-full">
        <div class="flex-1 text-center p-1 bg-white rounded-lg m-1 cursor-pointer flex lg:bg-gradient-to-r lg:from-green-400 lg:to-white bg-green-400" id="info">
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-bar-chart-line w-6 m-2 text-white" viewBox="0 0 16 16">
                <path d="M11 2a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h1V7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7h1V2zm1 12h2V2h-2v12zm-3 0V7H7v7h2zm-5 0v-3H2v3h2z"/>
            </svg>
            <h1 class="text-2xl mt-1 font-bold text-white">統計</h1>
        </div>
        <div class="flex-1 text-center p-1 bg-white rounded-lg m-1 cursor-pointer flex lg:bg-gradient-to-r lg:from-blue-400 lg:to-white bg-blue-400" id="history">
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-list w-6 m-2 text-white" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
            </svg>
            <h1 class="text-2xl mt-1 font-bold text-white">決済履歴</h1>
        </div>
        <div class="flex-1 text-center p-1 bg-white rounded-lg m-1 cursor-pointer flex lg:bg-gradient-to-r lg:from-pink-400 lg:to-white bg-pink-400" id="record">
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-pencil w-6 m-2 text-white" viewBox="0 0 16 16">
                <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
            </svg>
            <h1 class="text-2xl mt-1 font-bold text-white">記録</h1>
        </div>
    </div>
    <div class="p-3 w-full lg:m-5 m-3 mt-3 rounded-lg h-full" id="info_screen">
        <div class="lg:flex p-2 bg-white">
            <div class="lg:w-1/3">                 
                <div class="h-32 m-2">
                    <h1 class="text-gray-600">残高</h1>
                    <h1 class="text-6xl m-2 font-bold" id="balance"></h1>
                </div>
                <hr>
                <div class="m-3 h-64 overflow-y-auto overflow-x-hidden">
                    <table class="table-auto w-full m-2 h-full" id="month_payment_list"></table>
                </div>
                <div class="flex m-3">
                    <h1 class="flex-1 text-xl">平均月間収益</h1>
                    <h1 class="flex-1 text-xl font-bold" id="ave_balance"></h1>
                </div>
            </div>
            <div class="lg:w-2/3 lg:block hidden">
                <canvas id="balance_chart"></canvas>
            </div>
        </div>
        <div class="inline-block relative w-40 mt-3">
            <select class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="select"></select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
            </div>
        </div>
        <div class="lg:flex">
            <div class="flex-1 p-2 bg-white mr-3 mt-3">
                <h1 class="w-12 text-xl text-gray-600">収入</h1>
                <div class="lg:flex">
                    <div class="lg:w-1/3 w-full">
                        <h1 class="lg:text-6xl text-3xl m-2 text-green-400 font-bold" id="income"></h1>
                        <div class="m-3" id="income_data"></div>
                    </div>
                    <div class="lg:w-2/3 w-full">
                        <canvas id="income_chart"></canvas>
                    </div>
                </div>
            </div>
            <div class="flex-1 p-2 bg-white lg:ml-3 mt-3">
                <h1 class="w-12 text-xl text-gray-600">支出</h1>
                <div class="lg:flex">
                    <div class="lg:w-1/3 w-full">
                        <h1 class="lg:text-6xl text-3xl m-2 text-red-400 font-bold" id="spending"></h1>
                        <div class="m-3" id="spending_data"></div>
                    </div>
                    <div class="lg:w-2/3 w-full">
                        <canvas id="spending_chart"></canvas>
                    </div>
                </div>               
            </div>
        </div>
        <canvas id="myLineChart"></canvas>
    </div>
    <div class="flex justify-center">
        <div class="p-3 md:w-1/2 bg-white m-5 rounded-lg w-full" id="record_screen">
            <h1 class="text-gray-400">記録</h1>
            <div class="my-2">
                <label class="block text-gray-500 font-bold mb-1 md:mb-0 pr-4">
                    種類
                </label>
                <input class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" id="type_input" value="臨時" type="text" disabled>
            </div>
            <div class="my-2">
                <label class="block text-gray-500 font-bold mb-1 md:mb-0 pr-4">
                    名前
                </label>
                <input class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" id="name_input" type="text">
            </div>
            <div class="my-2">
                <label class="block text-gray-500 font-bold mb-1 md:mb-0 pr-4">
                    収益
                </label>
                <input class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" id="action_input" type="number">
                <h1 id="decision_balance">決済後の残高：</h1>
            </div>
            <input type="button" id="decision_btn" class="shadow bg-blue-500 hover:bg-blue-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded" value="決定">
        </div>
    </div>
    <div class="p-3 bg-white m-5 rounded-lg h-full" id="history_screen">
        <h1 class="text-gray-400">決済履歴</h1>
        <table class="table-fixed w-full text-center" id="payments_list">
            <thead>
                <tr>
                <th class="px-4 py-2 ">ID</th>
                <th class="px-4 py-2 ">名前</th>
                <th class="px-4 py-2 lg:table-cell hidden">種類</th>
                <th class="px-4 py-2 cursor-pointer">決済</th>
                <th class="px-4 py-2 cursor-pointer">収益</th>
                <th class="px-4 py-2 cursor-pointer lg:table-cell hidden">メモ</th>
                <th class="px-4 py-2 cursor-pointer lg:table-cell hidden">練習ID</th>
                <th class="px-4 py-2 cursor-pointer lg:table-cell hidden">決済日時</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($payments_list as $payment){?>
                    <tr>
                        <td class="border px-4 py-2"><?php echo $payment["id"];?></td>
                        <td class="border px-4 py-2"><?php echo $payment["name"];?></td>
                        <td class="border px-4 py-2 lg:table-cell hidden"><?php echo $payment["type"];?></td>
                        <td class="border px-4 py-2"><?php echo $payment["balance"];?></td>
                        <td class="border px-4 py-2"><?php echo $payment["action"];?></td>
                        <td class="border px-4 py-2 lg:table-cell hidden"><?php echo $payment["memo"];?></td>
                        <td class="border px-4 py-2 lg:table-cell hidden"><?php echo $payment["practice_id"];?></td>
                        <td class="border px-4 py-2 lg:table-cell hidden"><?php echo $payment["update_date"];?></td>
                    </tr>
                <?php }?>
            </tbody>
        </table>
    </div>
    <?php include "../layout/script.html";?>
    <script>
        payment = <?php echo $payments?>;
        latest_id = "<?php echo $latest_id?>";
        date_id = <?php echo $date?>;
    </script>
    <script type="text/javascript" src="./index.js"></script>
</body>
</html>