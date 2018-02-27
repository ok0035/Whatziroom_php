<?php

// include_once("../DBConnector.php");
include_once $_SERVER["DOCUMENT_ROOT"]."/whatziroom/DB/DBConnector.php";
$classDB = new ClassDB();
$conn=$classDB->getConn();
if(!$conn){
  die();
}

$searchData = isset($_POST["query"]) ? $_POST["query"] : "";
$searchQuery = "";

if($searchData != "") {
  $searchQuery = "select Room.PKey as RoomPKey, Room.Name as RoomName, Description, Room.CreatedDate as RoomCreateDate, Room.UpdatedDate as RoomUpdatedDate, User.Name as FounderName from Room, User where Room.FounderKey = User.PKey and Room.Name like '%$searchData%' and Room.Status = 0 order by Room.CreatedDate desc;";

} else {
  $searchQuery = "select Room.PKey as RoomPKey, Room.Name as RoomName, Description, Room.CreatedDate as RoomCreateDate, Room.UpdatedDate as RoomUpdatedDate, User.Name as FounderName from Room, User where Room.FounderKey = User.PKey and Room.Status = 0 order by Room.CreatedDate desc;";
}

$searchSQL = mysqli_query($conn, $searchQuery) or die(mysqli_error($conn));
$roomList = array();

while($row = mysqli_fetch_array($searchSQL)) {
  array_push($roomList, array(

    'PKey'=>$row['RoomPKey'],
    'Name'=>$row['RoomName'],
    'Description'=>$row['Description'],
    'CreatedDate'=>$row['RoomCreatedDate'],
    'UpdatedDate'=>$row['RoomUpdatedDate'],
    'FounderName'=>$row['FounderName']
  ));
}
// echo $searchQuery;
echo json_encode($roomList, JSON_UNESCAPED_UNICODE);

?>
