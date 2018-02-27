<?php

// include_once("../DBConnector.php");
include_once $_SERVER["DOCUMENT_ROOT"]."/whatziroom/DB/DBConnector.php";
$classDB = new ClassDB();
$conn=$classDB->getConn();
if(!$conn){
  die();
}

$userPKey = isset($_POST["UserPKey"]) ? $_POST["UserPKey"] : 0;
$roomPKey = isset($_POST["RoomPKey"]) ? $_POST["RoomPKey"] : 0;

if($userPKey == 0 || $roomPKey == 0) echo "fail_PKey";

else {
  //거절할 인원이 있는지 확인( 혹시 모르니까.. )
  $checkUserQuery = "select UserKey from UserRoomList where UserKey = $userPKey and RoomKey = $roomPKey and Status = 1";
  $checkUserSQL = mysqli_query($conn, $checkUserQuery) or die(mysqli_error($conn));
  $checkUser = mysqli_num_rows($checkUserSQL);

  //신청중인 인원이 있다면 Status를 4로 바꿔준다.
  if($checkUser > 0) {

    $updateStatusQuery = "update UserRoomList set Status = 4 where UserKey = $userPKey and RoomKey = $roomPKey and Status = 1";
    $updateStatusSQL = mysqli_query($conn, $updateStatusQuery) or die(mysqli_error($conn));

    echo "success";

  } else echo "fail"; // 신청중인 인원이 없다는 뜻이므로 만약 이곳에 오면 클라이언트에는 리스트가 존재하는데 서버에는 존재하지 않는 경우이다.

}



?>
