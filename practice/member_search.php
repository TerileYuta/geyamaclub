<?php 
    require "../config.php";

    $name = $_POST['name'];

    $member_list = ORM::for_table("member")->where_like("name","%". $name. "%")->find_many();

    $res_list = array(); 

    foreach ($member_list as $member){
        array_push($res_list, array("line_id" => $member->line_id, "name" => $member->name));
    }

    echo json_encode($res_list);
?>