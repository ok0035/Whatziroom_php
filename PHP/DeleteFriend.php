<?php

// include_once("../DBConnector.php");
include_once $_SERVER["DOCUMENT_ROOT"]."/whatziroom/DB/DBConnector.php";
$classDB = new ClassDB();
$conn=$classDB->getConn();
if(!$conn){
  die();
}

if(isset($_POST["UserPKey"]) && isset($_POST["FriendKey"])){
  $UserPKey = $_POST["UserPKey"];
  $FriendKey = $_POST["FriendKey"];
}else{
  echo "POST_ERROR";
}


$deleteFriend_query = "Update Friend Set Status = 2 where PKey = (
select PKey from (select * from Friend) as Temp Where (UserPKey = $UserPKey and FriendKey = $FriendKey and Status = 1) or (UserPKey = $FriendKey and FriendKey = $UserPKey and Status = 1) 
) ;";

$deleteFriend_sql = mysqli_query($conn,$deleteFriend_query);

if($deleteFriend_sql){
  echo "DELETE_SUCCESS";
}else{
echo "FAIL ".$deleteFriend_query;
}

?>
