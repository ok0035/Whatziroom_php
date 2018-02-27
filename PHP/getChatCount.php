<?php

// include_once("../DBConnector.php");
include_once $_SERVER["DOCUMENT_ROOT"]."/whatziroom/DB/DBConnector.php";
$classDB = new ClassDB();
$conn=$classDB->getConn();
if(!$conn){
  die();
}

$userPKey = isset($_POST["UserPKey"]) ? $_POST["UserPKey"] : 0;

if($roomPKey == 0 || $userPKey == 0) echo "fail_PKey";

else {

  $selectChatCountQuery = "select Count from ChatCount where UserKey = '$userPKey'";
  $selectChatCountSQL = mysqli_query($conn, $selectChatCountQuery);

  $selectChatCount = mysqli_fetch_row($selectChatCountSQL)[0];

  echo $selectChatCount;

}


?>
