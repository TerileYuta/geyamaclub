<?php
    require "../config.php";

    $memo = $_POST["memo"];
    $practice_id = $_POST["practice_id"];
    
    $plan = ORM::for_table("plan")->where("id", $practice_id)->find_one();
    $plan->memo = $memo;
    $plan->save();
?>