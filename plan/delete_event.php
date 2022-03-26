<?php
    require_once "../config.php";
    require_once "../idiorm.php";   

    $date = $_POST['date'];

    $plan = ORM::for_table('plan')->where('date', $date)->find_one();
    $plan -> delete();
?>