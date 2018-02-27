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
$chatCount = isset($_POST["ChatCount"]) ? $_POST["ChatCount"] : -1;

if($roomPKey == 0 || $userPKey == 0 || $chatCount == -1) echo "fail_PKey";

else {

  $selectChatCountQuery = "select ChatCount from UserRoomList where RoomKey = $roomPKey and UserKey = $userPKey";
  $selectChatCountSQL = mysqli_query($conn, $selectChatCountQuery) or die(mysqli_error($conn));
  $selectChatCount = mysqli_num_rows($selectChatCountSQL);

  if($selectChatCount == 1) {

    $updateChatCountQuery = "update UserRoomList set ChatCount = $chatCount where RoomKey = '$roomPKey' and UserKey = '$userPKey'";
    $updateChatCountSQL = mysqli_query($conn, $updateChatCountQuery) or die(mysqli_error($conn));

    echo "success";

  } else echo $selectChatCount;
}

?>
