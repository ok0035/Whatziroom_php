<?php

// include_once("../DBConnector.php");
include_once $_SERVER["DOCUMENT_ROOT"]."/whatziroom/DB/DBConnector.php";
$classDB = new ClassDB();
$conn=$classDB->getConn();
if(!$conn){
  die();
}

$UserPKey = isset($_POST["UserPKey"]) ? $_POST["UserPKey"] : "0";

$selectUserList_query = "select * from User where PKey in (Select FriendKey FROM Friend where UserPKey = $UserPKey And Status = 1)
union
select * from User where PKey in (Select UserPKey FROM Friend where FriendKey = $UserPKey and Status = 1);";
$selectUserList_sql = mysqli_query($conn, $selectUserList_query);

$selectUserList_result = array();

while($row = mysqli_fetch_array($selectUserList_sql)){
  array_push($selectUserList_result, array(
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
    'UDID'=>$row['UDID']
  ));
}

echo json_encode($selectUserList_result,JSON_UNESCAPED_UNICODE);

mysqli_close($conn);
?>
