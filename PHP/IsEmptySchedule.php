<?php

// include_once("../DBConnector.php");
include_once $_SERVER["DOCUMENT_ROOT"]."/whatziroom/DB/DBConnector.php";
$classDB = new ClassDB();
$conn=$classDB->getConn();
if(!$conn){
  die();
}

$roomPKey = isset($_POST["PKey"]) ? $_POST["PKey"] : 0;

$selectRoomQuery = "select * from Schedule, Room
where Schedule.RoomPkey = Room.PKey
and Schedule.RoomPkey = $roomPKey
and Room.Status = 0
and Schedule.Status = 0
and Schedule.Time - now() >= 0";

$selectRoomSQL = mysqli_query($conn, $selectRoomQuery);
$selectRoom = mysqli_num_rows($selectRoomSQL);

if($selectRoom == 0) {
  echo "empty";
} else echo "notEmpty";

?>
