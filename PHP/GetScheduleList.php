<?php

// include_once("../DBConnector.php");
include_once $_SERVER["DOCUMENT_ROOT"]."/whatziroom/DB/DBConnector.php";
$classDB = new ClassDB();
$conn=$classDB->getConn();
if(!$conn){
  die();
}

$userPKey = isset($_POST["UserPKey"]) ? $_POST["UserPKey"] : 0;
$limit = isset($_POST["Limit"]) ? "LIMIT ".$_POST["Limit"] : "";

//방이 활성화되어있고 스케줄에 참석하는 경우.
$ScheduleListQuery = "select Schedule.RoomPKey as RoomPKey, Schedule.PKey as SchedulePKey, Room.Name as Title, Schedule.Name as Name,
                      Schedule.Place, Schedule.`Time` as 'Time'
                      from Room,UserRoomList, User, Schedule, UserScheduleList
                      where UserRoomList.UserKey = User.PKey
                      and Room.PKey = UserRoomList.RoomKey
                      and Schedule.RoomPkey = Room.PKey
                      and User.PKey = UserScheduleList.UserKey
                      and Schedule.Pkey = UserScheduleList.ScheduleKey
                      and Room.Status = 0
                      and Schedule.Status = 0
                      and UserScheduleList.Status = 1
                      and (UserRoomList.Status = 0 or UserRoomList.Status = 2)
                      and UserScheduleList.UserKey = $userPKey
                      and Time - now() >= 0
                      order by Time - now()
                      $limit";

$ScheduleListSQL = mysqli_query($conn, $ScheduleListQuery);
$ScheduleList = array();

while($row = mysqli_fetch_array($ScheduleListSQL)) {
  array_push($ScheduleList, array(

    'RoomPKey'=>$row['RoomPKey'],
    'SchedulePKey'=>$row['SchedulePKey'],
    'Title'=>$row['Title'],
    'Name'=>$row['Name'],
    'Place'=>$row['Place'],
    'Time'=>$row['Time']

  ));
}

echo json_encode($ScheduleList, JSON_UNESCAPED_UNICODE);


?>
