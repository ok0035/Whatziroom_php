<?php

// include_once("../DBConnector.php");
include_once $_SERVER["DOCUMENT_ROOT"]."/whatziroom/DB/DBConnector.php";
$classDB = new ClassDB();
$conn=$classDB->getConn();
if(!$conn){
  die();
}

$userPKey = isset($_POST["UserPKey"]) ? $_POST["UserPKey"] : 0;
$schedulePKey = isset($_POST["SchedulePKey"]) ? $_POST["SchedulePKey"] : 0;

if($schedulePKey == 0 || $userPKey == 0) echo "fail_PKey";

$checkQuery = "select PKey from UserScheduleList
where UserScheduleList.Status = 1
and ScheduleKey = $schedulePKey
and UserKey = $userPKey";

$checkSQL = mysqli_query($conn, $checkQuery) or die(mysqli_error($conn));
$checkNumber = mysqli_num_rows($checkSQL);

//만약 참석 확정인 필드가 있다면 이미 존재하는 것!
if($checkNumber > 0) {

  $updateQuery = "update UserScheduleList set Status = 0
  where ScheduleKey = $schedulePKey
  and UserKey = $userPKey
  and Status = 1";

  $updateSQL = mysqli_query($conn, $updateQuery) or die(mysqli_error($conn));

  echo "success";

} else echo "fail";

?>
