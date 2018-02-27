<?php

// include_once("../DBConnector.php");
include_once $_SERVER["DOCUMENT_ROOT"]."/whatziroom/DB/DBConnector.php";
$classDB = new ClassDB();
$conn=$classDB->getConn();
if(!$conn){
  die();
}

$schedulePKey = isset($_POST["SchedulePKey"]) ? $_POST["SchedulePKey"] : 0;

if($schedulePKey == 0) echo "fail_PKey";

$getUserQuery = "select User.PKey as PKey, User.Name as Name, Longitude, Latitude, FirebaseToken from UserScheduleList, User
where UserScheduleList.UserKey = User.PKey
and ScheduleKey = $schedulePKey
and UserScheduleList.Status = 1";

$getUserSQL = mysqli_query($conn, $getUserQuery) or die(mysqli_error($conn));

$getUser = array();

while($row = mysqli_fetch_array($getUserSQL)) {
  array_push($getUser, array(

    'PKey' => $row['PKey'],
    'Name' => $row['Name'],
    'Longitude' => $row['Longitude'],
    'Latitude' => $row['Latitude'],
    'FirebaseToken' => $row['FirebaseToken']

  ));

}

echo json_encode($getUser, JSON_UNESCAPED_UNICODE);

?>
