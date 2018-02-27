<?php

// include_once("../DBConnector.php");
include_once $_SERVER["DOCUMENT_ROOT"]."/whatziroom/DB/DBConnector.php";
$classDB = new ClassDB();
$conn=$classDB->getConn();
if(!$conn){
  die();
}

if(isset($_POST["UserPKey"])){
  $UserPKey = $_POST["UserPKey"];
}else{
  echo "POST_ERROR";
}

$getFriendStatus_query = "Select Friend.PKey as PKey, FriendKey, Friend.Status, User.Name as Name,'send' as Flag, Friend.SenderRead as isRead from Friend, User where Friend.FriendKey = User.PKey and UserPKey = $UserPKey and Friend.SenderRead = 0
union Select Friend.PKey as PKey, UserPKey as FriendKey, Friend.Status, User.Name as Name, 'receive' as Flag, Friend.ReceiverRead as isRead FROM Friend, User where Friend.UserPKey = User.PKey and FriendKey = $UserPKey and Friend.ReceiverRead = 0;";

// echo $getFriendStatus_query;

$getFriendStatus_sql = mysqli_query($conn,$getFriendStatus_query);
$getFriendStatus_result = array();
while($row = mysqli_fetch_array($getFriendStatus_sql)){
  array_push($getFriendStatus_result,
  array('friend'=>array(
      'PKey' => $row['PKey'],
      'FriendKey'=>$row['FriendKey'],
      'Status'=>$row['Status'],
      'Name'=>$row['Name'],
      'Flag'=>$row['Flag']
    )));
}

$getRoomStatus_query =  "select UserRoomList.PKey as TablePKey, User.Name as UserName,User.PKey as UserPKey, Room.Name as RoomName,Room.PKey as RoomPKey ,'Requester' as Flag, UserRoomList.Status as Status, UserRoomList.SenderRead as IsRead
from User, UserRoomList, Room
  where User.PKey = UserRoomList.UserKey
  and UserRoomList.RoomKey = Room.PKey
  and UserRoomList.Status in (1,2)
  and User.PKey = $UserPKey
  and UserRoomList.SenderRead = 0

  union

  select UserRoomList.PKey as TablePKey, User.Name as UserName, User.PKey as UserPKey, Room.Name as RoomName,Room.PKey as RoomPKey ,'Accepter' as Flag, UserRoomList.Status as Status, UserRoomList.ReceiverRead as IsRead
  from Room, User, UserRoomList
  where User.PKey = UserRoomList.UserKey
  and UserRoomList.RoomKey = Room.PKey
  and UserRoomList.Status in (1,2)
  and Room.FounderKey = $UserPKey
  and UserRoomList.ReceiverRead = 0;";

  $getRoomStatus_sql = mysqli_query($conn,$getRoomStatus_query);
  $getRoomStatus_result = array();
  while($row = mysqli_fetch_array($getRoomStatus_sql)){
    array_push($getRoomStatus_result,
    array('room'=>array(
        'PKey' => $row['TablePKey'],
        'UserName'=>$row['UserName'],
        'UserPKey'=>$row['UserPKey'],
        'RoomName'=>$row['RoomName'],
        'RoomPKey'=>$row['RoomPKey'],
        'Flag'=>$row['Flag'],
        'Status'=>$row['Status'],
        'IsRead'=>$row['IsRead']
      )));
  }

  $finalArray = array("Friend"=>$getFriendStatus_result, "Room"=> $getRoomStatus_result);

echo json_encode($finalArray,JSON_UNESCAPED_UNICODE);


mysqli_close($conn);
?>
