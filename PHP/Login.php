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
$selectPW_sql = mysqli_query($conn, $selectPW_query);
$selectPW_result = mysqli_fetch_row($selectPW_sql);
$selectPW = $selectPW_result[0];

//솔팅 작업하자~
$salt = MD5($PW)."dragonball";
$saltingPW = $PW.$salt;
//기존 가입했던 테스팅 아이디의 비밀번호는 Bcrypt로 암호화가 되어있지 않기 때문에 둘다 체크해준다. 딱히 문제될 일은 없다.
//둘중 하나만 맞아도 else문으로 가기 때문에...
if(!password_verify($PW, $selectPW) && !password_verify($saltingPW, $selectPW)) echo "fail";

else {
  //여기부턴 로그인이 성공했다는 뜻이므로 UUID와 FirebaseToken를 업데이트 해준다.
  $updateUUIDQuery = "update User set UUID = '$uuid', FirebaseToken = '$firebaseToken' where ID = '$ID'";
  $updateUUID_SQL = mysqli_query($conn, $updateUUIDQuery) or die(mysqli_error($conn));

  $selectUserQuery = "select * from User where ID = '$ID'";
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
      'Message'=>$row['Message'],
      'FirebaseToken'=>$row['FirebaseToken']
    ));
  }
  echo json_encode($result, JSON_UNESCAPED_UNICODE);

}

?>
