

<?php


require_once($_SERVER['DOCUMENT_ROOT'].'/A09001_schedule/includes/define.php');




////////////////////////////////////////////新規スケジュール登録  受け取り 関連////////////////////////////////////////////



//----変数の初期化------//

//schedule_data
$schedule_id         = null;
$title               = null;

$start_datetime_year = null;
$start_datetime_month= null;
$start_datetime_day  = null;
$start_datetime_hour = null;
$start_datetime_mins = null;
$start_datetime      = null;

$end_datetime_year   = null;
$end_datetime_month  = null;
$end_datetime_day    = null;
$end_datetime_hour   = null;
$end_datetime_mins   = null;
$end_datetime        = null;

$place               = null;
$detail              = null;

$insert_datetime     = null;
$update_datetime     = null;

//schedule_member
$employee_id[]       = null;


//コマンド各種
$insert              = null;
$update              = null;
$delete              = null;



//-----変数の格納------//


//schedule_data

//--タイトル--//

if(isset($_POST['schedule_id'])){
    $schedule_id = $_POST['schedule_id'];
    echo $schedule_id;
}

if(isset($_POST['title'])){
    $title = $_POST['title'];
    echo $title;
}

//--開始日時--//
if(isset($_POST['start_datetime_year'])){
    $start_datetime_year = $_POST['start_datetime_year'];
}
if(isset($_POST['start_datetime_month'])){
    $start_datetime_month = $_POST['start_datetime_month'];
}
if(isset($_POST['start_datetime_day'])){
    $start_datetime_day = $_POST['start_datetime_day'];
}
if(isset($_POST['start_datetime_hour'])){
    $start_datetime_hour = $_POST['start_datetime_hour'];
}
if(isset($_POST['start_datetime_mins'])){
    $start_datetime_mins = $_POST['start_datetime_mins'];
}

$start_datetime = date( "Y/m/d H:i:s",mktime($start_datetime_hour,$start_datetime_mins,0,$start_datetime_month,$start_datetime_day,$start_datetime_year) );
echo $start_datetime;

//--終了日時--//
if(isset($_POST['end_datetime_year'])){
    $end_datetime_year = $_POST['end_datetime_year'];
}
if(isset($_POST['end_datetime_month'])){
    $end_datetime_month = $_POST['end_datetime_month'];
}
if(isset($_POST['end_datetime_day'])){
    $end_datetime_day = $_POST['end_datetime_day'];
}
if(isset($_POST['end_datetime_hour'])){
    $end_datetime_hour = $_POST['end_datetime_hour'];
}
if(isset($_POST['end_datetime_mins'])){
    $end_datetime_mins = $_POST['end_datetime_mins'];
}

$end_datetime = date( "Y/m/d H:i:s",mktime($end_datetime_hour,$end_datetime_mins,0,$end_datetime_month,$end_datetime_day,$end_datetime_year) );
echo $end_datetime;

//--場所--//
if(isset($_POST['place'])){
    $place = $_POST['place'];
    echo $place;
}

//--詳細--//
if(isset($_POST['detail'])){
    $detail = $_POST['detail'];
    echo $detail;
}

//--登録日時--//
if(isset($_POST['insert_datetime'])){
    $insert_datetime = $_POST['insert_datetime'];
    echo $insert_datetime;
}

//--更新日時--//
if(isset($_POST['update_datetime'])){
    $update_datetime = $_POST['update_datetime'];
    echo $update_datetime;
}

//--社員ID--//
if(isset($_POST["employee_id"])){
    $employee_ids = $_POST["employee_id"];
 
}



//--コマンド関連--//
if(isset($_POST['insert'])){
    $insert = $_POST['insert'];
}

if(isset($_POST['update'])){
    $update = $_POST['update'];
    echo $update;
}

if(isset($_POST['delete'])){
    $delete = $_POST['delete'];
}




   foreach($employee_ids as $employee_id){
    echo var_dump($employee_ids);}





//------------データベース接続--------------//


    try{
        $pdo = new PDO(DSN,CONNECT_USER,CONNECT_PASS);
    }catch(PDOException $e){
        // 接続失敗
    var_dump($e->getMessage());
    exit;
}




/////////////////////////////週間カレンダー用のデータ/////////////////////////////

//------------SELECT(employ_masterからデータ取得）--------------//
$stmt = $pdo->prepare('SELECT employee_id,name FROM `employee_master`');
$stmt->execute();
$employee_records = $stmt->fetchAll(PDO::FETCH_ASSOC);


//------------SELECT(schedule_memberとschedule_data結合してからデータ取得）--------------//

$stmt = $pdo->prepare('SELECT d.schedule_id,d.title,d.place,d.detail,d.start_datetime,d.end_datetime,d.insert_datetime,d.update_datetime,m.schedule_id,m.employee_id FROM `schedule_data` d,`schedule_member` m WHERE d.schedule_id = m.schedule_id ;');
$stmt->execute();
$schedule_records = $stmt->fetchAll(PDO::FETCH_ASSOC);





/*
//------------スケジュール重複のチェック --------------//

$stmt = $pdo->prepare('SELECT d.schedule_id,d.start_datetime,d.end_datetime,m.schedule_id,m.employee_id FROM `schedule_data` d,`schedule_member` m WHERE d.schedule_id = m.schedule_id;');
$stmt->execute();
$check_schedule_records = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach($check_schedule_records as $check_schedule_record){
    $check_employee_id    = $check_schedule_record['employee_id'] ;
    //データベースからスケジュール開始日時、終了日時、社員ID 取得  
    $stmt = null;

for($i=0;$i<count($check_schedule_records);$i++){

    
  if ($start_datetime < $check_schedule_records[$i]['end_datetime'] && $check_schedule_records[$i]['start_datetime'] < $end_datetime ) {

        echo "スケジュールが重複しています！";
        echo var_dump($start_datetime);
        echo var_dump($check_schedule_records[$i]['end_datetime'  ]);
        echo var_dump($check_schedule_records[$i]['start_datetime']);
        echo var_dump($end_datetime);
//if($check_employee_id == $employee_id)

 } 
 }

}
*/



//---------------------もしinsertボタンが押されたら、、、------------------------//
if($insert != null){



//-----SELECT (schedule_idを取得し、＋1することで次のschedule_idに割り当てる)---------//
$stmt = $pdo->prepare("SELECT MAX(schedule_id) from `schedule_data`");
$stmt->execute();
$max_schedule_ids = $stmt->fetch(PDO::FETCH_ASSOC);
foreach($max_schedule_ids as $max_schedule_id){
$next_schedule_id = $max_schedule_id+1;
}

$stmt = null;

//-----INSERT---------//


//--schedule_data--//
$stmt = $pdo->prepare('INSERT INTO `schedule_data`(schedule_id,title,place,detail,start_datetime,end_datetime,insert_datetime,update_datetime)VALUES(?,?,?,?,?,?,?,?)');

$stmt->bindParam(1,$next_schedule_id,PDO::PARAM_STR);
$stmt->bindParam(2,$title,           PDO::PARAM_STR);
$stmt->bindParam(3,$place,           PDO::PARAM_STR);
$stmt->bindParam(4,$detail,          PDO::PARAM_STR);
$stmt->bindParam(5,$start_datetime,  PDO::PARAM_STR);
$stmt->bindParam(6,$end_datetime,    PDO::PARAM_STR);
$stmt->bindParam(7,$insert_datetime, PDO::PARAM_STR);
$stmt->bindParam(8,$update_datetime, PDO::PARAM_STR);

$stmt->execute();
$stmt = null;
echo "done!";


$stmt2 = $pdo->prepare('INSERT INTO `schedule_member`(schedule_id,employee_id,insert_datetime,update_datetime)VALUES(?,?,?,?)');

$stmt2->bindParam(1,$next_schedule_id,  PDO::PARAM_STR);
foreach ($employee_ids as $employee_id) {
$stmt2->bindParam(2,$employee_id,       PDO::PARAM_STR);
$stmt2->bindParam(3,$insert_datetime,   PDO::PARAM_STR);
$stmt2->bindParam(4,$update_datetime,   PDO::PARAM_STR);
$stmt2->execute();
}
$stmt2=null;

}







////////////////////////////////////////////スケジュール更新・削除 受け取り 関連////////////////////////////////////////////



//---------------------もしupdateボタンが押されたら、、、------------------------//
if($update != null){


//-----UPDATE---------//



//--schedule_dataの更新--//
$stmt = $pdo->prepare('UPDATE `schedule_data` SET title = ?,place = ?,detail = ?,start_datetime = ?,end_datetime = ?,insert_datetime = ?,update_datetime = ? WHERE schedule_id=?');


$stmt->bindParam(1,$title,           PDO::PARAM_STR);
$stmt->bindParam(2,$place,           PDO::PARAM_STR);
$stmt->bindParam(3,$detail,          PDO::PARAM_STR);
$stmt->bindParam(4,$start_datetime,  PDO::PARAM_STR);
$stmt->bindParam(5,$end_datetime,    PDO::PARAM_STR);
$stmt->bindParam(6,$insert_datetime, PDO::PARAM_STR);
$stmt->bindParam(7,$update_datetime, PDO::PARAM_STR);
$stmt->bindParam(8,$schedule_id,    PDO::PARAM_STR);

$stmt->execute();
echo "schedule_data update done!";


$stmt = null;


//--schedule_memberの更新--//
$stmt2 = $pdo->prepare('UPDATE `schedule_member` SET employee_id = ?,insert_datetime = ?,update_datetime = ? WHERE schedule_id=?');

foreach ($employee_ids as $employee_id) {
$stmt2->bindParam(1,$employee_id, 　　　　　PDO::PARAM_STR);
$stmt2->bindParam(2,$insert_datetime,     PDO::PARAM_STR);
$stmt2->bindParam(3,$update_datetime,     PDO::PARAM_STR);
$stmt2->bindParam(4,$schedule_id,         PDO::PARAM_STR);
$stmt2->execute();
}


$stmt2 = null;

}




//---------------------もしdeleteボタンが押されたら、、、------------------------//
if($delete != null){


//-----DELETE--------//


//--schedule_dataの削除--//
$stmt = $pdo->prepare('DELETE FROM `schedule_data` WHERE schedule_id = ?');

$stmt->bindParam(1,$schedule_id,     PDO::PARAM_STR);

$stmt->execute();
echo "schedule_data delete done!";

//--schedule_memberの削除--//
$stmt2 = $pdo->prepare('DELETE FROM `schedule_member` WHERE schedule_id = ? ');

$stmt2->bindParam(1,$schedule_id,         PDO::PARAM_STR);

$stmt2->execute();
echo "schedule_member delete done!";

}




////////////////////////////////////////////スケジュール更新・削除 データ送付 関連////////////////////////////////////////////



 





////////////////////////////////////////////月カレンダー 関連////////////////////////////////////////////



// timeStamp
$ym = isset($_GET['ym']) ? $_GET['ym'] : date("Y-m");
 
$timeStamp2 = strtotime($ym . "-01");
 
if ($timeStamp2 === false) {
    $timeStamp2 = time();
}
 
// 前月、翌月
 
$prev_month = date("Y-m", mktime(0,0,0,date("m",$timeStamp2)-1,1,date("Y",$timeStamp2)));
$next_month = date("Y-m", mktime(0,0,0,date("m",$timeStamp2)+1,1,date("Y",$timeStamp2)));
 

//今日
$today      = date("Y-m-d", mktime(0,0,0,date("m",time()),date("d",time()),date("Y",time())));



// 最終日？
 
$lastDay = date("t", $timeStamp2);
 
// 1日は何曜日？
// 0: Sun ... 6: Sat
 
$youbi = date("w", mktime(0,0,0,date("m",$timeStamp2),1,date("Y",$timeStamp2)));
 
 
$weeks = array();
$week = '';
 
$week .= str_repeat('<td></td>', $youbi);
 
for ($day = 1; $day <= $lastDay; $day++, $youbi++) {
    $week .= sprintf('<td class="youbi_%d">%d</td>', $youbi % 7, $day);
 
    if ($youbi % 7 == 6 OR $day == $lastDay) {
        if ($day == $lastDay) {
            $week .= str_repeat('<td></td>', 6 - ($youbi % 7));
        }
        $weeks[] = '<tr>' . $week . '</tr>';
        $week = '';
    }
}


 

////////////////////////////////////////////週カレンダー 関連////////////////////////////////////////////

// timeStamp
$ymd = isset($_GET['ymd']) ? $_GET['ymd'] : date("Y-m-d");
 
$timeStamp = strtotime($ymd);
 
if ($timeStamp === false) {
    $timeStamp = time();
}



//今日
$today      = date("Y-m-d");

//昨日、明日
$yesterday = date("Y-m-d", mktime(0,0,0,date("m",$timeStamp),date("d",time())-1,date("Y",$timeStamp)));
$tomorrowbb= date("Y-m-d", mktime(0,0,0,date("m",$timeStamp),date("d",$timeStamp)+1,date("Y",$timeStamp)));


// 先週、来襲
 
$prev_week = date("Y-m-d", mktime(0,0,0,date("m",$timeStamp),date("d",time())-7,date("Y",$timeStamp)));
$next_week = date("Y-m-d", mktime(0,0,0,date("m",$timeStamp),date("d",time())+7,date("Y",$timeStamp)));

 
?>



    <!--<table border="1">
            <tr>
                <th><a href="?ymd=<?php //echo htmlspecialchars($prev_week,ENT_QUOTES,'UTF-8'); ?>">&laquo;&laquo;</a></th>
                    <th><a href="?ymd=<?php //echo htmlspecialchars($yesterday,ENT_QUOTES,'UTF-8'); ?>">&laquo;</a></th>
                                <th><a href="?ymd=<?php //echo htmlspecialchars($today,ENT_QUOTES,'UTF-8'); ?>">今日</a></th>
                    <th><a href="?ymd=<?php //echo htmlspecialchars($tomorrow,ENT_QUOTES,'UTF-8'); ?>">&raquo;</a></th>
                <th><a href="?ymd=<?php //echo htmlspecialchars($next_week,ENT_QUOTES,'UTF-8'); ?>">&raquo;&raquo;</a></th>
            </tr>
    </table>
-->



<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>メイン画面</title>
</head>
<body>
<h1>スケジュール帳 </h1>

<!---月カレンダ-->

    <table align="right" border="1">
            <tr>
                <th><a href="?ym=<?php echo htmlspecialchars($prev_month,ENT_QUOTES,'UTF-8'); ?>">&laquo;</a></th>
                <th colspan="5"><?php echo htmlspecialchars(date("Y",$timeStamp) . "-" . date("m", $timeStamp),ENT_QUOTES,'UTF-8'); ?></th>
                <th><a href="?ym=<?php echo htmlspecialchars($next_month,ENT_QUOTES,'UTF-8'); ?>">&raquo;</a></th>
            </tr>
            <tr>
                <th>日</th>
                <th>月</th>
                <th>火</th>
                <th>水</th>
                <th>木</th>
                <th>金</th>
                <th>土</th>
            </tr>
            <?php
                foreach ($weeks as $week) {
                    echo $week;
                }
            ?>
            <tr>
            <td colspan="7" align="center">
            <a href="?ym=<?php echo htmlspecialchars($today,ENT_QUOTES,'UTF-8'); ?>">今日</a>
            </td>
            </tr>
    </table>

<!--週カレンダ-->
<form method="POST" action="make_schedule.php">
<table border="1">
   <!---日付-->
    <tr>
   <!---左上の空セルを定義-->
    <th width="70"></th>

   <!---日付のセルを定義-->
    <?php for($i=0;$i<7;$i++) { ?>
            <th width="70" bgcolor="FF9999">
            <?php echo date("m/d(D)", mktime(0,0,0,date("m"),date("d")+$i,date("Y"))) ;?>
            </th>
    <?php } ;?>



    </tr>
     <!--社員名-->
    <?php foreach($employee_records as $employee_record){ ?>
    <tr>
    <td>
    <?php echo $employee_record['name']; ?>
    </td>
    <?php for($i=0;$i<7;$i++) { 
    $cal_start_datetime = $schedule_records[$i]['start_datetime'];
    $cal_end_datetime = $schedule_records[$i]['end_datetime'];
    echo var_dump($schedule_records[$i]['start_datetime']);
     ?>
    <td>
    <?php echo date("H:i",strtotime($cal_start_datetime)); ?> - <?php echo date("H:i",strtotime($cal_end_datetime)); ?><br>
    <a href="edit_schedule.php?schedule_id=<?php echo  $schedule_records[$i]['schedule_id'] ?>">
    <?php echo $schedule_records[$i]['title'] ; ?>
    </a>
    <br>
    <input type="submit" name="make_schedule" value="新規" > 
        <!--更新用スケジュールに送るデータ-->
    <input type="hidden" name="schedule_id"   value="<?php echo $schedule_records[$i]['schedule_id']  ;?>"> 
    </td>
    <?php } ;?>
    </tr>
    <?php } ;?>

</table>
</body>
</html>


</body>
</html>