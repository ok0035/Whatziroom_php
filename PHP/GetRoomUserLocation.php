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

$getLocationQuery = "select User.Longitude, User.Latitude from User, UserScheduleList
where User.PKey = UserScheduleList.UserKey
and UserScheduleList.Status = 1
and UserScheduleList.ScheduleKey = 98;";

$getLocationSQL = mysqli_query($conn, $getLocationQuery) or die(mysqli_error($conn));

$getLocation = array();

while($row = mysqli_fetch_array($getLocationSQL)) {
  array_push($getLocation, array(

    'Longitude'=>$row['Longitude'],
    'Latitude'=>$row['Latitude']

  ));
}

echo json_encode($getLocation, JSON_UNESCAPED_UNICODE);

?>
