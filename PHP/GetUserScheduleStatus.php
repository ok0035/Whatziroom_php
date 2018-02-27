<?php

// include_once("../DBConnector.php");
include_once $_SERVER["DOCUMENT_ROOT"]."/whatziroom/DB/DBConnector.php";
$classDB = new ClassDB();
$conn=$classDB->getConn();
if(!$conn){
  die();
}
//CheckAttendSchedule.php는 참석 버튼을 눌렀을 경우.
//GetUserCheduleStatus.php 는 방에 입장 했을때 참석 여부를 확인하여 버튼을 활성화 여부를 판단하는 경우에 사용한다.
$userPKey = isset($_POST["UserPKey"]) ? $_POST["UserPKey"] : 0;
$schedulePKey = isset($_POST["SchedulePKey"]) ? $_POST["SchedulePKey"] : 0;

if($schedulePKey == 0 || $userPKey == 0) echo "fail_PKey";

else {

  //UserScheduleList가 존재하는지 체크한다.
  $checkStatusQuery = "select Status from UserScheduleList
  where ScheduleKey = $schedulePKey
  and UserKey = $userPKey";

  $checkStatusSQL = mysqli_query($conn, $checkStatusQuery) or die(mysqli_error($conn));
  $checkNumber = mysqli_num_rows($checkStatusSQL);

  if($checkNumber > 0) {

    //0보다 크다는 것은 등록되어 있는 필드가 있다는 뜻이므로 필드를 echo로 보내준다.
    $selectStatusSQL = mysqli_query($conn, $checkStatusQuery) or die(mysqli_error($conn));
    $selectStatus = mysqli_fetch_row($selectStatusSQL);

    echo "$selectStatus[0]";

  } else {

    //필드가 0개라는 것은 필드가 존재하지 않는다는 것이므로 불참 상태라는 이야기이다.
    //필드가 Status = 0 인 필드를 생성해주고 echo로 0을 보내준다.
    $insertQuery = "insert into UserScheduleList values(null, $schedulePKey, $userPKey, 0)";
    $insertSQL = mysqli_query($conn, $insertQuery) or die(mysqli_error($conn));

    echo "0";
  }

}

?>
