<?php

// include_once("../DBConnector.php");
include_once $_SERVER["DOCUMENT_ROOT"]."/whatziroom/DB/DBConnector.php";
$classDB = new ClassDB();
$conn=$classDB->getConn();
if(!$conn){
  die();
}

if(isset($_POST["FriendPKey"]) && isset($_POST["Status"])){
  $FriendPKey  = $_POST["FriendPKey"];
  $Status  = $_POST["Status"];
}else{
  echo "POST_ERROR";
}



switch ($Status) {
  case '-1':
    deleteStatus();
    # code...
    break;

  case "1":
  updateStatus($Status);
    break;

  case "2":
    updateStatus($Status);
    break;
}

mysqli_close($conn);


function updateStatus($status){

  global $conn, $FriendPKey;

  $updateFriendStatus_query = "UPDATE Friend SET Status = $status, UpdatedDate = Now() where PKey = $FriendPKey";

  $updateFriendStatus_sql = mysqli_query($conn, $updateFriendStatus_query);

  if($updateFriendStatus_sql){
    echo 'UPDATE_SUCCESS';
  }else{
    echo 'UPDATE_FAIL'.$updateFriendStatus_query;
  }

}

function deleteStatus(){
  global $conn, $FriendPKey;

  $deleteFriendStatus_query = "DELETE FROM Friend where PKey = $FriendPKey";

  $deleteFriendStatus_sql = mysqli_query($conn,$deleteFriendStatus_query);

  if($deleteFriendStatus_sql){
    echo 'DELETE_SUCCESS';
  }else{
    echo 'DELETE_FAIL'.$deleteFriendStatus_query;
  }
}


?>
