<?php
    require_once "../config.php";
    require_once "../idiorm.php";
    require_once "../log/add_log.php";

    $title = $_POST["title"];
    $income = (int) $_POST['income'];
    $spending = (int) $_POST['spending'];
    $date = $_POST['date'];
    $practice_id = $_POST['practice_id'];
    $fee = (int) $_POST['fee'];
    $income_other = (int) $_POST['income_other'];
    $income_othrt_memo = $_POST['income_other_memo'];
    $gym = (int) $_POST['gym'];
    $shattle = (int) $_POST['shattle'];
    $other = (int) $_POST['other'];
    $other_memo = $_POST['other_memo'];

    $max_id = ORM::for_table('payments')->max("id");
    $balance = ORM::for_table('payments')->where(array('id' => $max_id))->find_one();
    $balance = $balance["balance"];

    $plan = ORM::for_table("plan")->where(array("date" => $date))->find_one();
    $plan->settlement_id = $practice_id;
    $plan->fee = $fee;
    $plan->income_other = $income_other;
    $plan->gym = $gym;
    $plan->shattle = $shattle;
    $plan->other = $other;
    $plan->save();

    if($fee > 0){
        $balance += $fee;
        $settlement = ORM::for_table("payments")->create();
        $settlement->practice_id = $practice_id;
        $settlement->name = $title;
        $settlement->action = "+". $fee;
        $settlement->balance = $balance;
        $settlement->type = "参加費";
        $settlement->save();
    }

    if($income_other > 0){       
        $balance += $income_other;
        $settlement = ORM::for_table("payments")->create();
        $settlement->practice_id = $practice_id;
        $settlement->name = $title;
        $settlement->action = "+". $income_other;
        $settlement->balance = $balance;
        $settlement->type = "その他の収入";
        $settlement-> memo = $income_other_memo;
        $settlement->save();
    }

    if($gym > 0){ 
        $balance -= $gym;
        $settlement = ORM::for_table("payments")->create();
        $settlement->practice_id = $practice_id;
        $settlement->name = $title;
        $settlement->action = "-". $gym;
        $settlement->balance = $balance;
        $settlement->type = "体育館代";
        $settlement->save();
    }

    if($shattle > 0){
        $balance -= $shattle;
        $settlement = ORM::for_table("payments")->create();
        $settlement->practice_id = $practice_id;
        $settlement->name = $title;
        $settlement->action = "-". $shattle;
        $settlement->balance = $balance;
        $settlement->type = "シャトル代";
        $settlement->save();
    }

    if($other > 0){
        $balance -= $other;
        $settlement = ORM::for_table("payments")->create();
        $settlement->practice_id = $practice_id;
        $settlement->name = $title;
        $settlement->action = "-". $other;
        $settlement->balance = $balance;
        $settlement->type = "その他の支出";
        $settlement-> memo = $other_memo;
        $settlement->save();
    
        echo $practice_id;
    }

    ORM::for_table('plan')->find_result_set()
    ->where(array("date" => $date))
    ->set('status', "Not Recruited")
    ->save();
    
    $plan = ORM::for_table('plan')->where(array("date" => $date))->find_one();
    $join_member_list = explode(".", $plan["member"]);
    array_shift($join_member_list);

    foreach($join_member_list as $name){
        $person = ORM::for_table('member')->where(array('name' => $name))->find_one();
        ORM::for_table('member')->find_result_set()
        ->where(array("name" => $name))
        ->set('attendance_number', $person["attendance_number"] + 1)
        ->set('last_entry_date', $date)
        ->save();
    }

    add_log("main", "練習ID". $practice_id. "の決済が確定されました");

    echo $practice_id;
?>