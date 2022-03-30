<?php
    require "../config.php";
    require "../decrypt.php";
    require "../log/add_log.php";

    $date = $_POST['date'];

    $plan = ORM::for_table('plan')->where('date', $date)->find_one();
    $plan -> delete();

    add_log("main", $date. "の練習予定が削除されました");
?>