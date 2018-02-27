<?php

// include_once("../DBConnector.php");
include_once $_SERVER["DOCUMENT_ROOT"]."/whatziroom/DB/DBConnector.php";
$classDB = new ClassDB();
$conn=$classDB->getConn();
if(!$conn){
  die();
}

$UserPKey = isset($_POST["UserPKey"]) ? $_POST["UserPKey"] : "0";
$FindText = isset($_POST["FindText"]) ? $_POST["FindText"] : "";

$findFriend_query = "select User.*, if(User.PKey in
  (Select FriendKey from Friend where UserPKey = $UserPKey and Status = 0), 'send_wating',
  if(User.PKey in
  (Select UserPKey from Friend where FriendKey = $UserPKey and Status = 0),'receive_wating','no_relation'))
  as FriendStatus
from User where PKey != $UserPKey
and PKey Not in (
  Select FriendKey from Friend where UserPKey = $UserPKey and Status = 1
  union
  Select UserPKey from Friend where FriendKey = $UserPKey and Status = 1
)
and Name like '$FindText%';";
$findFriend_sql = mysqli_query($conn,$findFriend_query);
$findFriend_result = array();

while($row = mysqli_fetch_array($findFriend_sql)){
  array_push($findFriend_result, array(
    'PKey'=>$row['PKey'],
    'Name'=>$row['Name'],
    'ID'=>$row['ID'],
    'PW'=>$row['PW'],
    'Email'=>$row['Email'],
    'Status'=>$row['Status'],
    'Acount'=>$row['Acount'],
    'Longitude'=>$row['Longitude'],
    'Latitude'=>$row['Latitude'],
    'CreatedDate'=>$row['CreatedDate'],
    'UpdatedDate'=>$row['UpdatedDate'],
    'UUID'=>$row['UUID'],
    'Message'=>$row['Message'],
    'FriendStatus'=>$row['FriendStatus']
  ));
}

echo json_encode($findFriend_result,JSON_UNESCAPED_UNICODE);
// echo $findFriend_query;

mysqli_close($conn);

?>
