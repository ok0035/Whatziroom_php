<?php

// include_once("../DBConnector.php");
include_once $_SERVER["DOCUMENT_ROOT"]."/whatziroom/DB/DBConnector.php";
$classDB = new ClassDB();
$conn=$classDB->getConn();
if(!$conn){
  die();
}

$userPKey = isset($_POST["UserPKey"]) ? $_POST["UserPKey"] : 0;
$uuid = isset($_POST["UUID"]) ? $_POST["UUID"] : 0;
$longitude = isset($_POST["Longitude"]) ? $_POST["Longitude"] : 0;
$latitude = isset($_POST["Latitude"]) ? $_POST["Latitude"] : 0;

$updateQuery = "";
if($userPKey == 'null' || $userPKey == 0)
  $updateQuery = "update User SET Longitude = $longitude, Latitude = $latitude, UpdatedDate = now() WHERE UUID = '$uuid';";
else
  $updateQuery = "update User SET Longitude = $longitude, Latitude = $latitude, UpdatedDate = now() WHERE PKey = '$userPKey';";

$updateSQL = mysqli_query($conn, $updateQuery) or die(mysqli_error($conn));

echo $updateQuery;

?>
