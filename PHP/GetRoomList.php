<?php

// include_once("../DBConnector.php");
include_once $_SERVER["DOCUMENT_ROOT"]."/whatziroom/DB/DBConnector.php";
$classDB = new ClassDB();
$conn=$classDB->getConn();
if(!$conn){
  die();
}

$userPKey = isset($_POST["UserPKey"]) ? $_POST["UserPKey"] : 0;

//Room.Status = 0 활성화 1 비활성화
//UserRoomList = 0 개설자, 1 신청중, 2 가입
$roomListQuery = "select Room.PKey as PKey, Room.Name as Name, Room.Description as Description, ChatCount,
Room.CreatedDate as CreatedDate, Room.UpdatedDate as UpdatedDate, User.Name as FounderName from Room,UserRoomList, User
where Room.FounderKey = User.PKey
and Room.PKey = UserRoomList.RoomKey
and Room.Status = 0
and (UserRoomList.Status = 0 or UserRoomList.Status = 2)
and UserKey = '$userPKey'
order by UpdatedDate desc";

$roomListSQL = mysqli_query($conn, $roomListQuery);
$roomList = array();

while($row = mysqli_fetch_array($roomListSQL)) {
  array_push($roomList, array(

    'PKey'=>$row['PKey'],
    'Name'=>$row['Name'],
    'Description'=>$row['Description'],
    'ChatCount'=>$row['ChatCount'],
    'CreatedDate'=>$row['CreatedDate'],
    'UpdatedDate'=>$row['UpdatedDate'],
    'FounderName'=>$row['FounderName']
  ));
}

echo json_encode($roomList, JSON_UNESCAPED_UNICODE);

?>
