<?php
    require_once "../config.php";
    require_once "../idiorm.php";  
    require_once "../log/add_log.php"; 

    $title = $_POST["title"];
    $date = $_POST["date"];
    $start_time = $_POST["start_time"];
    $end_time = $_POST["end_time"];
    $place = $_POST["place"];

    $add_event = ORM::for_table("plan")->create();
    $add_event -> title = $title;
    $add_event -> date = $date;
    $add_event -> time = $start_time. "-". $end_time;
    $add_event -> place = $place;
    $add_event -> save();

    add_log("main", $title. "-". $date. "-(". $start_time. "-". $end_time. ")が追加されました");
?>