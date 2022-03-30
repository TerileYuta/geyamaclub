<?php
    require "../config.php";

    $name = $_POST['name'];
    $id = $_POST['id'];

    $new_member = ORM::for_table("member")->where("name", $name)->find_one();
    $line_id = $new_member->line_id;

    $practice = ORM::for_table("plan")->where("id", $id)->find_one();
    $practice_member = $practice["member"];

    if($practice_member == ""){
        ORM::for_table('plan')->where("id", $id)->find_result_set()
        ->set('member', $line_id. ".")
        ->save();
    }else{
        if(!in_array($line_id, explode(".", $practice_member))){
            ORM::for_table('plan')->where("id", $id)->find_result_set()
            ->set('member', $line_id. ".". $practice_member)
            ->save();
        }
    }

?>