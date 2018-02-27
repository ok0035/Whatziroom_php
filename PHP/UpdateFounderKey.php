<?php

// include_once("../DBConnector.php");
include_once $_SERVER["DOCUMENT_ROOT"]."/whatziroom/DB/DBConnector.php";
$classDB = new ClassDB();
$conn=$classDB->getConn();
if(!$conn){
  die();
}

$checkQuery = "select * from Room";
$sqlCheckQuery = mysqli_query($conn, $checkQuery);

while($row = mysqli_fetch_array($sqlCheckQuery)) {

// echo $row['FounderKey'];
  $founder = $row['FounderKey'];

  if($founder == 0 || $founder == '0') {

    $RoomPKey = $row['PKey'];
    // echo $RoomPKey;

    $founderKeyQuery = "select UserKey from Room, UserRoomList where UserRoomList.RoomKey = Room.PKey and UserRoomList.Status = 0 and Room.PKey = $RoomPKey";
    $founderKeySQL = mysqli_query($conn, $founderKeyQuery);
    $founderKey = mysqli_fetch_row($founderKeySQL)[0];
    //
    echo $founderKey;
    //

    if($founderKey != "") {
      $updateQuery = "update Room SET FounderKey = $founderKey WHERE PKey = $RoomPKey";
      $updateSQL = mysqli_query($conn, $updateQuery) or die(mysqli_error($conn));
    }


  }

  // echo $row['Name'].' '.$row['FounderKey'].', ';

}

?>
