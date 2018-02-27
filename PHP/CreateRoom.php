<?php

// include_once("../DBConnector.php");
include_once $_SERVER["DOCUMENT_ROOT"]."/whatziroom/DB/DBConnector.php";
$classDB = new ClassDB();
$conn=$classDB->getConn();
if(!$conn){
  die();
}

$userPKey = isset($_POST["PKey"]) ? $_POST["PKey"] : 0;
$roomName = isset($_POST["Name"]) ? $_POST["Name"] : "0";
$roomDescription = isset($_POST["Description"]) ? $_POST["Description"] : "0";
// echo '유저키 : '.$userPKey;

if($userPKey != 0) {

  $createRoomQuery = "insert into Room values(null, '$roomName', '$roomDescription', 0, now(), now(), $userPKey);";
  $createRoomSQL = mysqli_query($conn, $createRoomQuery) or die(mysqli_error($conn));

  $lastInsertID_query = "select LAST_INSERT_ID() as PKey;";
  $lastInsertID_sql = mysqli_query($conn, $lastInsertID_query) or die(mysqli_error($conn));
  $lastInsertID_result = mysqli_fetch_row($lastInsertID_sql);
  $lastInsertID = $lastInsertID_result[0];

  $roomPKey = $lastInsertID;

  $createUserRoomListQuery = "insert into UserRoomList values(null, $userPKey, $roomPKey, now(), now(), 0, 0,0,0);";
  $createUserRoomListSQL = mysqli_query($conn, $createUserRoomListQuery) or die(mysqli_error($conn));

  $lastInsertID_sql = mysqli_query($conn, $lastInsertID_query) or die(mysqli_error($conn));
  $lastInsertID_result = mysqli_fetch_row($lastInsertID_sql);
  $lastInsertID = $lastInsertID_result[0];

  $createOptionQuery = "insert into UserRoomOption values (null, $userPKey, $roomPKey, 0, 1);";
  $createOptionSQL = mysqli_query($conn, $createOptionQuery) or die(mysqli_error($conn));

  echo $roomPKey;


}


?>
