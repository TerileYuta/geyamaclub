<?php
  require_once "../config.php";
  require_once "../idiorm.php";
  require_once "../decrypt.php";
  require_once "../log/add_log.php";

  if(key_check(basename(dirname(__FILE__)))){
    try {
      $plan_db = ORM::for_table('plan')->find_many();
      $plan_list = array();

      foreach ($plan_db as $plan) {
        $list = array(
          "id" => $plan["date"],
          "title" => $plan["title"],
          "start" => $plan["date"],
          "time" => $plan["time"],
        );

        array_push($plan_list, $list);
      }

      $plan_list = json_encode($plan_list);
    } catch (Exception $e) {
      add_log("error", "データベース接続エラー || " . $e);
    }
  }else{
      add_log("main", "planページのキー認証に失敗しました");
      header("Location: ../Error");
  }

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <?php include "../layout/head.html";?>
</head>
<body class="w-full h-full bg-gray-200 p-2 m-5" style="overflow: auto;">
  <!--nobanner-->
  <div class="md:flex bg-white p-3 rounded-lg">
    <div class="flex md:w-2/3 w-full">
      <div id='calendar' class="w-full"></div>
    </div>
    <div class="lg:flex lg:w-1/3 w-full lg:m-3">
      <div class="w-full lg:m-2">
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline m-1" id="event_title" type="text" placeholder="イベントタイトル" value="通常練習">
        <h1 class="text-3xl m-1" id="event_start">YYYY年MM月DD日</h1>
        <div class="flex m-1 w-full">
          <div class="flex-1">
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="start_time" type="time" value="18:30">
          </div>
          <div class="flex-1">
            <h1 class="text-3xl text-center">~</h1>
          </div>
          <div class="flex-1">
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="end_time" type="time" value="21:00">
          </div>
        </div>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline m-1" id="place" type="text" placeholder="場所" value="中央小学校">
        <span class="text-xl m-1">詳細：</span><a class="no-underline hover:underline text-blue-500 text-xl" href="" target="_blank" target="_blank" id="practice_url"></a>
        <div class="flex w-full m-1">
          <div class="flex-1 m-1">
            <input type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded cursor-pointer w-full" value="追加" id="add_event">
          </div>
          <div class="flex-1 m-1">
            <input type="button" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded cursor-pointer w-full" value="削除" id="delete_event">
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php include "../layout/script.html";?>
  <script>
    plan_list = <?php echo $plan_list ?>;
  </script>
  <script type="text/javascript" src="./index.js"></script>
</body>

</html>