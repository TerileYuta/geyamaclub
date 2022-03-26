<?php
    require_once "../config.php";
    require_once "../idiorm.php";

    $name = $_POST['name'];
    $type = $_POST['type'];
    $action = $_POST['action'];

    $max_id = ORM::for_table('payments')->max("id");
    $balance = ORM::for_table('payments')->where(array('id' => $max_id))->find_one();
    $balance = $balance["balance"];

    if(substr($action, 1, 1) == "-"){
        $balance -= (int) substr($action, 1);
    }else{
        $balance += (int) $action;
        $action = "+". $action;
    }

    $new_settlement = ORM::for_table('payments')->create();
    $new_settlement -> settlement_id = 0;
    $new_settlement -> name = $name;
    $new_settlement -> type = $type;
    $new_settlement -> action = $action;
    $new_settlement -> balance = $balance;
    $new_settlement -> save();
?>