<?php

// include_once("../DBConnector.php");
include_once $_SERVER["DOCUMENT_ROOT"]."/whatziroom/DB/DBConnector.php";
$classDB = new ClassDB();
$conn=$classDB->getConn();
if(!$conn){
  die();
}

$roomPKey = isset($_POST["RoomPKey"]) ? $_POST["RoomPKey"] : 0;

$getRequestUserQuery = "select User.PKey as UserPKey, UserRoomList.RoomKey as RoomKey, User.Name as Name from UserRoomList, User
where UserRoomList.UserKey = User.PKey
and UserRoomList.Status = 1
and UserRoomList.RoomKey = $roomPKey;";

$getRequestUserSQL = mysqli_query($conn, $getRequestUserQuery);

$getRequestUser = array();

while($row = mysqli_fetch_array($getRequestUserSQL)) {

    array_push($getRequestUser, array(

      'UserPKey'=>$row['UserPKey'],
      'RoomPKey'=>$row['RoomKey'],
      'Name'=>$row['Name']

    ));
}

echo json_encode($getRequestUser, JSON_UNESCAPED_UNICODE);

?>
