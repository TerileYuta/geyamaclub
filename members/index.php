<?php
    require_once "../config.php";
    require_once "../idiorm.php";
    require_once "../log/add_log.php";

    try{
        $all_member = ORM::for_table('member')->find_many();
        $member_list = array();

        foreach($all_member as $member){
            $total_practice = $records = ORM::for_table('plan')->where_gt('date', $member["admission_date"])->count();

            if(($member["attendance_number"] != 0) && ($total_practice != 0)){
                $join_parcent = round(($member["attendance_number"] / $total_practice) * 100);
            }else{
                $join_parcent = "-";
            }
            
            $member_info = array("id" => $member["id"],
                                "name" => $member["name"],
                                "attendance" => $member["attendance_number"],
                                "last_entry" => $member["last_entry_date"],
                                "join_at" => $member["admission_date"],
                                "total_practice" => $total_practice,
                                "join_parcent" => $join_parcent,
                                "memo" => $member["memo"]);

            array_push($member_list, $member_info);
        }
    }catch(Exception $e){
        add_log("Error", $_SERVER["REMOTE_ADDR"], "サーバー接続エラー||". $e);
        header("Location: ../Error");
    }

    $count = 0;

?>
<!DOCTYPE html>
<head>
    <?php include "../layout/head.html";?>
</head>
<html lang="ja">
<body class="w-full h-full bg-gray-200" style="overflow: auto;">
    <!--nobanner-->
    <div class="p-2 bg-white m-5 rounded-lg">
        <h1 class="text-gray-400">Member</h1>
        <table class="table-auto w-full">
            <thead>
                <tr>
                <th class="px-4 py-2">ID</th>
                <th class="px-4 py-2">氏名</th>
                <th class="px-4 py-2">参加回数</th>
                <th class="px-4 py-2">開催数</th>
                <th class="px-4 py-2">参加率</th>
                <th class="px-4 py-2">最終参加日</th>
                <th class="px-4 py-2">加入日</th>
                <th class="px-4 py-2">メモ</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    foreach($member_list as $member){  
                        if($count % 2 == 0){
                ?>
                    <tr class="bg-gray-100">
                <? }else{?>
                    <tr></tr>
                <?php }?>
                    <td class="border px-4 py-2"><?php echo $member["id"];?></td>
                    <td class="border px-4 py-2"><?php echo $member["name"];?></td>
                    <td class="border px-4 py-2"><?php echo $member["attendance"];?></td>
                    <td class="border px-4 py-2"><?php echo $member["total_practice"];?></td>
                    <td class="border px-4 py-2"><?php echo $member["join_parcent"];?>%</td>
                    <td class="border px-4 py-2"><?php echo $member["last_entry"];?></td>
                    <td class="border px-4 py-2"><?php echo $member["join_at"];?></td>
                    <td class="border px-4 py-2"><?php echo $member["memo"];?></td>
                </tr>
                <?php $count ++; } ?>
            </tbody>
        </table>
    </div>
    <?php include "../layout/script.html";?>
    <script type="text/javascript" src="./index.js"></script>
</body>
</html>