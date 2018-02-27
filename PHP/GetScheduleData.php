<?php

// include_once("../DBConnector.php");
include_once $_SERVER["DOCUMENT_ROOT"]."/whatziroom/DB/DBConnector.php";
$classDB = new ClassDB();
$conn=$classDB->getConn();
if(!$conn){
  die();
}

$roomPKey = isset($_POST["RoomPKey"]) ? $_POST["RoomPKey"] : 0;
$limit = isset($_POST["Limit"]) ? "LIMIT ".$_POST["Limit"] : "";
// $schedulePKey = isset($_POST["SchedulePKey"]) ? $_POST["SchedulePKey"] : 0;

if($roomPKey == 0) {
  echo "fail";
} else {

  $scheduleDataQuery = "select Schedule.PKey as 'SchedulePKey', Schedule.Name as 'Title', Schedule.Description as 'Description', Schedule.Time as 'Time', Place,
                        Schedule.Longitude as Longitude, Schedule.Latitude as Latitude ,Schedule.MakerUserKey as MakerUserKey, User.Name as 'Name', Schedule.ImageURL as 'ImageURL', Schedule.OldAddress as 'OldAddress',
                        Schedule.NewAddress as 'NewAddress', Schedule.TEL as 'TEL', Schedule.URL as 'WURL'  from Schedule, User
                        where User.PKey = Schedule.MakerUserKey
                        and Schedule.Status = 0
                        and RoomPkey = $roomPKey
                        and Time - now() >= 0
                        order by Time - now()
                        $limit";

  $scheduleDataSQL = mysqli_query($conn, $scheduleDataQuery) or die(mysqli_error($conn));
  $result = array();

  while($row = mysqli_fetch_array($scheduleDataSQL)) {
    array_push($result, array(

      'SchedulePKey'=>$row['SchedulePKey'],
      'Status'=>$row['Status'],
      'Title'=>$row['Title'],
      'MakerUserKey'=>$row['MakerUserKey'],
      'Name'=>$row['Name'],
      'Description'=>$row['Description'],
      'Time'=>$row['Time'],
      'Place'=>$row['Place'],
      'Longitude'=>$row['Longitude'],
      'Latitude'=>$row['Latitude'],
      'ImageURL'=>$row['ImageURL'],
      'OldAddress'=>$row['OldAddress'],
      'NewAddress'=>$row['NewAddress'],
      'TEL'=>$row['TEL'],
      'WURL'=>$row['WURL']

    ));
  }

  echo json_encode($result, JSON_UNESCAPED_UNICODE);

}

?>
