<?php

// include_once("../DBConnector.php");
include_once $_SERVER["DOCUMENT_ROOT"]."/whatziroom/DB/DBConnector.php";
$classDB = new ClassDB();
$conn=$classDB->getConn();
if(!$conn){
  die();
}
$SchedulePKey = isset($_POST["SchedulePKey"]) ? $_POST["SchedulePKey"] : 0;

$deleteScheduleQuery = "update Schedule set Status = 1 where Pkey = $SchedulePKey;";
$deleteUserScheduleListQuery = "update UserScheduleList set Status = 0 where ScheduleKey = $SchedulePKey;";

mysqli_query($conn, $deleteScheduleQuery) or die(mysqli_error($conn));
mysqli_query($conn, $deleteUserScheduleListQuery) or die(mysqli_error($conn));

echo "success";
?>
