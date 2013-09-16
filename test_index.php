

<?php


require_once($_SERVER['DOCUMENT_ROOT'].'/A09001_schedule/includes/define.php');


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
$yesterday = date("Y-m-d", mktime(0,0,0,date("m",$timeStamp),date("d",$timeStamp)-1,date("Y",$timeStamp)));
$tomorrow= date("Y-m-d", mktime(0,0,0,date("m",$timeStamp),date("d",$timeStamp)+1,date("Y",$timeStamp)));


// 先週、来襲
 
$prev_week = date("Y-m-d", mktime(0,0,0,date("m",$timeStamp),date("d",$timeStamp)-7,date("Y",$timeStamp)));
$next_week = date("Y-m-d", mktime(0,0,0,date("m",$timeStamp),date("d",$timeStamp)+7,date("Y",$timeStamp)));


/////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////


//各種タイムスタンプ変換　これらを比較して、スケジュールを当てはめる。

$today_timeStamp     = strtotime($today);

$yesterday_timeStamp = strtotime($yesterday);
$tomorrow_timeStamp  = strtotime($tomorrow);

$prev_week_timeStamp = strtotime($prev_week);
$next_week_timeStamp = strtotime($next_week);

 
/////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////


?>



    <table border="1">
            <tr>
                <th><a href="?ymd=<?php echo htmlspecialchars($prev_week,ENT_QUOTES,'UTF-8'); ?>">&laquo;&laquo;</a></th>
                    <th><a href="?ymd=<?php echo htmlspecialchars($yesterday,ENT_QUOTES,'UTF-8'); ?>">&laquo;</a></th>
                                <th><a href="?ymd=<?php echo htmlspecialchars($today,ENT_QUOTES,'UTF-8'); ?>">今日</a></th>
                    <th><a href="?ymd=<?php echo htmlspecialchars($tomorrow,ENT_QUOTES,'UTF-8'); ?>">&raquo;</a></th>
                <th><a href="?ymd=<?php echo htmlspecialchars($next_week,ENT_QUOTES,'UTF-8'); ?>">&raquo;&raquo;</a></th>
            </tr>
    </table>







<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>メイン画面</title>
</head>
<body>
<h1>スケジュール帳 </h1>

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
            <?php echo date("m/d(D)", mktime(0,0,0,date("m",$timeStamp),date("d",$timeStamp)+$i,date("Y",$timeStamp))) ;?>
            </th>
    <?php } ;?>



    </tr>
     <!--社員名-->
    <?php foreach($employee_records as $employee_record){ ?>
    <tr>
    <td>
    <?php echo $employee_record['name']; ?>
    </td>


    <!--スケジュール-->
    <?php for($i=0;$i<7;$i++) { 
    $cal_start_datetime = $schedule_records[$i]['start_datetime'];
    $cal_end_datetime = $schedule_records[$i]['end_datetime'];
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
