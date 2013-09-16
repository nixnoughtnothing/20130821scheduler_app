<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/A09001_schedule/includes/define.php');






//------------データベース接続--------------///


    try{
        $pdo = new PDO(DSN,CONNECT_USER,CONNECT_PASS);
    }catch(PDOException $e){
        // 接続失敗
    var_dump($e->getMessage());
    exit;
}

$stmt = $pdo->prepare("SELECT * from `employee_master`");
$stmt->execute();

$employee_records = $stmt->fetchALL(PDO::FETCH_ASSOC);






//------------タイムスタンプ関連--------------//


$today=getdate();

//タイムゾーン調整
$ENV{'TZ'} = "JST-9";   

?>



<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>新規スケジュール登録</title>
    <link rel="stylesheet" href="style.css" >
</head>
<body>
<h1>新規スケジュール登録</h1>
<form method="POST" action="indexindex.php">

<!--タイトルはメイン画面に送って表示する-->
<div>
タイトル : <input type="text" name="title" size="70">
</div>
<br>

<!--開始日時-->
<div>
開始日時 : 
        <select name="start_datetime_year" ><?php for($year =2013;$year <=2015;$year++  ) { ?><option <?php if($year == date(Y)) { echo "selected";} ?>><?php echo $year ;?></option><?php } ;?></select>年
        <select name="start_datetime_month"><?php for($month=1   ;$month<=12  ;$month++ ) { ?><option <?php if($month== date(m)) { echo "selected";} ?>><?php echo $month;?></option><?php } ;?></select>月
        <select name="start_datetime_day"  ><?php for($day  =1   ;$day  <=31  ;$day++   ) { ?><option <?php if($day  == date(d)) { echo "selected";} ?>><?php echo $day  ;?></option><?php } ;?></select>日
        <select name="start_datetime_hour" ><?php for($hour =0   ;$hour <=24  ;$hour++  ) { ?><option <?php if($hour == 10     ) { echo "selected";} ?>><?php echo $hour ;?></option><?php } ;?></select>時
        <select name="start_datetime_mins" ><?php for($mins =0   ;$mins <=59  ;$mins+=15) { ?><option <?php if($mins == 0      ) { echo "selected";} ?>><?php echo $mins ;?></option><?php } ;?></select>分
</div>


<!--終了日時-->
<div>
終了日時 : 
        <select name="end_datetime_year" ><?php for($year2 =2013;$year2 <=2015;$year2++  ) { ?><option <?php if($year2 == date(Y)){ echo "selected";} ?>><?php echo $year2 ;?></option><?php } ;?></select>年
        <select name="end_datetime_month"><?php for($month2=1   ;$month2<=12  ;$month2++ ) { ?><option <?php if($month2== date(m)){ echo "selected";} ?>><?php echo $month2;?></option><?php } ;?></select>月
        <select name="end_datetime_day"  ><?php for($day2  =1   ;$day2  <=31  ;$day2++   ) { ?><option <?php if($day2  == date(d)){ echo "selected";} ?>><?php echo $day2  ;?></option><?php } ;?></select>日
        <select name="end_datetime_hour" ><?php for($hour2 =0   ;$hour2 <=24  ;$hour2++  ) { ?><option <?php if($hour2 == 19     ){ echo "selected";} ?>><?php echo $hour2 ;?></option><?php } ;?></select>時
        <select name="end_datetime_mins" ><?php for($mins2 =0   ;$mins2 <=59  ;$mins2+=15) { ?><option <?php if($mins2 == 0      ){ echo "selected";} ?>><?php echo $mins2 ;?></option><?php } ;?></select>分
</div>
<br>

<!--場所-->
<div>
        場所&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <input type="text" name="place" size="70">
</div>
<br>

<!--詳細-->
<div>
        詳細&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <textarea name="detail" rows="5" cols="50"></textarea></li>
</div>
<br>

<!--登録先-->
<div>
    登録先&nbsp;&nbsp;&nbsp;&nbsp;:
    <?php foreach($employee_records as $employee_record) { ?>
        <input type="checkbox" name="employee_id[]" value="<?php echo $employee_record["employee_id"] ?>" checked><?php echo $employee_record['name'] ;?>
</div>
<br>
<?php } ; ?>

<!--登録日時-->
<div>
登録日時 : <input type="text" name="insert_datetime" size="70" value="<?php echo date( "Y/m/d H:i:s", time() ) ?>" readonly>
</div>

<!--更新日時-->
<div>   
更新日時 : <input type="text" name="update_datetime" size="70" value="<?php echo date( "Y/m/d H:i:s", time() ) ?>" readonly>
</div>
<br>

<!--登録ボタン-->
<input type="submit" name="insert" value="登録">
<br>

<!--戻るボタン-->
<input type="submit" name="back" value="戻る">
<br><br>

</form>
</body>
</html>