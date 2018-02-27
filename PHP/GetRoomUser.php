<?php

// include_once("../DBConnector.php");
include_once $_SERVER["DOCUMENT_ROOT"]."/whatziroom/DB/DBConnector.php";
$classDB = new ClassDB();
$conn=$classDB->getConn();
if(!$conn){
  die();
}

$roomPKey = isset($_POST["RoomPKey"]) ? $_POST["RoomPKey"] : 0;

$getRoomUserQuery = "select User.PKey as UserPKey, UserRoomList.RoomKey as RoomKey, User.Name as Name from UserRoomList, User
where UserRoomList.UserKey = User.PKey
and (UserRoomList.Status = 0 or UserRoomList.Status = 2)
and UserRoomList.RoomKey = $roomPKey;";

$getRoomUserSQL = mysqli_query($conn, $getRoomUserQuery);

$getRoomUser = array();

while($row = mysqli_fetch_array($getRoomUserSQL)) {

    array_push($getRoomUser, array(

      'UserPKey'=>$row['UserPKey'],
      'RoomPKey'=>$row['RoomKey'],
      'Name'=>$row['Name']

    ));
}

echo json_encode($getRoomUser, JSON_UNESCAPED_UNICODE);

?>
