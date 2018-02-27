<?php

// include_once("../DBConnector.php");
include_once $_SERVER["DOCUMENT_ROOT"]."/whatziroom/DB/DBConnector.php";
$classDB = new ClassDB();
$conn=$classDB->getConn();
if(!$conn){
  die();
}

if(isset($_POST["PKey"]) && isset($_POST["Flag"])){
  $PKey = $_POST["PKey"];
  $Flag = $_POST["Flag"];

}else{
  echo "POST_ERROR";
}


switch ($Flag) {
  case 'send':
    $updateRead = "Update Friend Set SenderRead = 1 where PKey = $PKey;";
    break;

  case 'receive':
    $updateRead = "Update Friend Set ReceiverRead = 1 where PKey = $PKey;";
    break;

  case 'Requester':
    $updateRead = "Update UserRoomList Set SenderRead = 1 where PKey = $PKey;";
    break;

  case 'Accepter':
    $updateRead = "Update UserRoomList Set ReceiverRead = 1 where PKey = $PKey;";
    break;

}

$updateRead_sql = mysqli_query($conn,$updateRead);
if($updateRead_sql){
  echo "UPDATE_SUCCESS";
}else{
  echo "UPDATE_FAIL".$updateRead;
}

?>
