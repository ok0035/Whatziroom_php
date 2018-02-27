<?php

// include_once("../DBConnector.php");
include_once $_SERVER["DOCUMENT_ROOT"]."/whatziroom/DB/DBConnector.php";
$classDB = new ClassDB();
$conn=$classDB->getConn();
if(!$conn){
  die();
}

$ID = isset($_POST["ID"]) ? $_POST["ID"] : "0";
$PW = isset($_POST["PW"]) ? $_POST["PW"] : "0";
$uuid = isset($_POST["UUID"]) ? $_POST["UUID"] : 0;
$firebaseToken = isset($_POST["FirebaseToken"]) ? $_POST["FirebaseToken"] : 0;

$selectPW_query = "select PW from User where ID = '$ID'";
$selectPW_sql = mysqli_query($conn, $selectPW_query) or die(mysqli_error($conn));
$selectPW_result = mysqli_fetch_row($selectPW_sql);
$selectPW = $selectPW_result[0];

if(strcmp($PW, $selectPW) == 0) {
  //UUID 업데이트
  $updateUUIDQuery = "update User set UUID = '$uuid', FirebaseToken = '$firebaseToken' where ID = '$ID'";
  $updateUUID_SQL = mysqli_query($conn, $updateUUIDQuery) or die(mysqli_error($conn));

  echo "success";
}

else "fail";

?>
