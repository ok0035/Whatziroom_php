<?php

// include_once("../DBConnector.php");
include_once $_SERVER["DOCUMENT_ROOT"]."/whatziroom/DB/DBConnector.php";
$classDB = new ClassDB();
$conn=$classDB->getConn();
if(!$conn){
  die();
}

$ID = isset($_POST["ID"]) ? $_POST["ID"] : 0;
// $ID = "test";
$PW = isset($_POST["PW"]) ? $_POST["PW"] : 0;
// $PW = "123123123";
$CPW = isset($_POST["CPW"]) ? $_POST["CPW"] : 0;
$name = isset($_POST["Name"]) ? $_POST["Name"] : 0;
$email = isset($_POST["Email"]) ? $_POST["Email"] : 0;
$uuid = isset($_POST["UUID"]) ? $_POST["UUID"] : 0;
$firebaseToken = isset($_POST["FirebaseToken"]) ? $_POST["FirebaseToken"] : 0;

if(checkDuplicateID()) {

  echo "duplicateID";

} else if (checkDuplicateEmail()) {

  echo "duplicateEmail";

} else if(checkDuplicateName()) {

  echo "duplicateName";

} else {
  $salt = MD5($PW)."dragonball";
  $PW = $PW.$salt; // 보안을 위해 솔팅 작업! MD5로 암호화한 뒤 고유한 값을 넣어야하는데 딱히 생각나는게 없어서 좋아하는 만화이름으로...

  $password_hash = password_hash($PW, PASSWORD_DEFAULT);
  $insertUserQuery = "insert into User values(null, '$name', '$ID', '$password_hash', '$email', 0, '0', 0, 0, now(), now(), '$uuid', '', '$firebaseToken')";
  $insertUserSQL = mysqli_query($conn, $insertUserQuery) or die(mysqli_error($conn));

  $lastInsertPKey_query = "select LAST_INSERT_ID() as PKey";
  $lastInsertPKey_sql = mysqli_query($conn, $lastInsertPKey_query);
  $lastInsertPKey_result = mysqli_fetch_row($lastInsertPKey_sql);
  $lastInsertPKey = $lastInsertPKey_result[0];

  $selectUserQuery = "select * from User where PKey = '$lastInsertPKey'";
  $selectUserSQL = mysqli_query($conn, $selectUserQuery);
  $result = array();
  while($row = mysqli_fetch_array($selectUserSQL)) {
    array_push($result, array(

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
      'FirebaseToken'=>$row['FirebaseToken']
    ));
  }

  echo json_encode($result, JSON_UNESCAPED_UNICODE);

}

function checkDuplicateID() {
  global $ID, $conn;

  $selectIDQuery = "select id from User where ID = '$ID'";
  $selectIDSQL = mysqli_query($conn, $selectIDQuery);
  $checkDuplicateID = mysqli_num_rows($selectIDSQL);

  if($checkDuplicateID > 0) return true;
  else return false;

}

function checkDuplicateName() {
  global $name, $conn;

  $selectNameQuery = "select id from User where Name = '$name'";
  $selectNameSQL = mysqli_query($conn, $selectNameQuery);
  $checkDuplicateName = mysqli_num_rows($selectNameSQL);

  if($checkDuplicateName > 0) return true;
  else return false;

}

function checkDuplicateEmail() {
  global $email, $conn;

  $selectEmailQuery = "select Email from User where Email = '$email'";
  $selectEmailSQL = mysqli_query($conn, $selectEmailQuery);
  $checkDuplicateEmail = mysqli_num_rows($selectEmailSQL);

  if($checkDuplicateEmail > 0) return true;
  else return false;

}

// mysqli_query($conn, $insertUserQuery);

?>
