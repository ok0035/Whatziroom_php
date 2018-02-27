<?php

// include_once("../DBConnector.php");
include_once $_SERVER["DOCUMENT_ROOT"]."/whatziroom/DB/DBConnector.php";
$classDB = new ClassDB();
$conn=$classDB->getConn();
if(!$conn){
  die();
}

if(isset($_POST["RoomListPKey"])){
  $RoomListPKey = $_POST["RoomListPKey"];

}else{
  echo "POST_ERROR";
}


$deleteRoomList_query = "Update UserRoomList set Status = 4 where PKey = $RoomListPKey;";

$deleteRoomList_sql = mysqli_query($conn,$deleteRoomList_query);

if($deleteFriend_sql){
  echo "DELETE_SUCCESS";
}else{
echo "FAIL ".$deleteRoomList_query;
}

?>
