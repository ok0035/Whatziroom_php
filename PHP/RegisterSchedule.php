<?php

// include_once("../DBConnector.php");
include_once $_SERVER["DOCUMENT_ROOT"]."/whatziroom/DB/DBConnector.php";
$classDB = new ClassDB();
$conn=$classDB->getConn();
if(!$conn){
  die();
}

$userPKey = isset($_POST["UserPKey"]) ? $_POST["UserPKey"] : 0;
$roomPKey = isset($_POST["RoomPKey"]) ? $_POST["RoomPKey"] : 0;
$name = isset($_POST["Name"]) ? $_POST["Name"] : 0;
$place = isset($_POST["Place"]) ? $_POST["Place"] : 0;
$time = isset($_POST["Date"]) ? $_POST["Date"] : 0;
$description = isset($_POST["Description"]) ? $_POST["Description"] : '';
$longitude = isset($_POST["Longitude"]) ? $_POST["Longitude"] : 0;
$latidue = isset($_POST["Latitude"]) ? $_POST["Latitude"] : 0;
$imageURL = isset($_POST["ImageURL"]) ? $_POST["ImageURL"] : '';
$oldAddress = isset($_POST["OldAddress"]) ? $_POST["OldAddress"] : '';
$newAddress = isset($_POST["NewAddress"]) ? $_POST["NewAddress"] : '';
$tel = isset($_POST["TEL"]) ? $_POST["TEL"] : '';
$url = isset($_POST["WURL"]) ? $_POST["WURL"] : '';

$insertScheduleQuery = "insert into Schedule values(null, $roomPKey, '$name', '$description',
                        0, '$time', $longitude, $latidue, now(), $userPKey, '$place', '$imageURL', '$oldAddress', '$newAddress', '$tel', '$url');";

mysqli_query($conn, $insertScheduleQuery) or die(mysqli_error($conn));

$lastInsertPKey_query = "select LAST_INSERT_ID() as PKey";
$lastInsertPKey_sql = mysqli_query($conn, $lastInsertPKey_query) or die(mysqli_error($conn));
$lastInsertPKey_result = mysqli_fetch_row($lastInsertPKey_sql);
$lastInsertPKey = $lastInsertPKey_result[0];

//스케줄을 만든 사람이기 때문에 당연히 참석으로 간주한다.
$insertUSListQuery = "insert into UserScheduleList values(null, $lastInsertPKey, $userPKey, 1);";
mysqli_query($conn, $insertUSListQuery) or die(mysqli_error($conn));

echo "fffff  ".$insertScheduleQuery;
?>
