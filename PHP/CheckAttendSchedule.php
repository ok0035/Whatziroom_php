<?php

// include_once("../DBConnector.php");
include_once $_SERVER["DOCUMENT_ROOT"]."/whatziroom/DB/DBConnector.php";
$classDB = new ClassDB();
$conn=$classDB->getConn();
if(!$conn){
  die();
}

$userPKey = isset($_POST["UserPKey"]) ? $_POST["UserPKey"] : 0;
$schedulePKey = isset($_POST["SchedulePKey"]) ? $_POST["SchedulePKey"] : 0;

if($schedulePKey == 0 || $userPKey == 0) echo "fail_PKey";

else {

  //불참으로 되어 있는것인지 아예 필드가 없는 것인지를 알아낸다.
  $checkQuery = "select PKey from UserScheduleList
  where UserScheduleList.Status = 0
  and ScheduleKey = $schedulePKey
  and UserKey = $userPKey";

  $checkSQL = mysqli_query($conn, $checkQuery) or die(mysqli_error($conn));
  $checkNumber = mysqli_num_rows($checkSQL);

  if($checkNumber > 0) {

    //0보다 크다는 것은 불참으로 등록되어 있는 필드가 있다는 뜻이므로 update 쿼리를 이용해서 UserScheduleList.Status를 1로 바꿔준다.
    //그전에, 참석으로 확정되있는 필드가 있는지 확인한다!

    $checkQuery = "select PKey from UserScheduleList
    where UserScheduleList.Status = 1
    and ScheduleKey = $schedulePKey
    and UserKey = $userPKey";

    $checkSQL = mysqli_query($conn, $checkQuery) or die(mysqli_error($conn));
    $checkNumber = mysqli_num_rows($checkSQL);

    //만약 참석 확정인 필드가 있다면 이미 존재하는 것!
    if($checkNumber > 0) {

      //이 경우 불참인 필드를 삭제해준다.
      $deleteQuery = "delete from UserScheduleList where UserKey = $userPKey and ScheduleKey = $schedulePKey";
      mysqli_query($conn, $deleteQuery);
      echo "already exist";

    } else {

      $updateQuery = "update UserScheduleList set Status = 1
      where ScheduleKey = $schedulePKey
      and UserKey = $userPKey
      and Status = 0";

      $updateSQL = mysqli_query($conn, $updateQuery) or die(mysqli_error($conn));

    }

    echo "success";

  } else {

    $checkQuery = "select PKey from UserScheduleList
    where UserScheduleList.Status = 1
    and ScheduleKey = $schedulePKey
    and UserKey = $userPKey";

    $checkSQL = mysqli_query($conn, $checkQuery) or die(mysqli_error($conn));
    $checkNumber = mysqli_num_rows($checkSQL);

    //만약 참석 확정인 필드가 있다면 이미 존재하는 것!
    if($checkNumber > 0) {

      echo "already exist";

    } else {

      //참석, 불참한 필드 모두 없으므로 생성해준다.
      $insertQuery = "insert into UserScheduleList values(null, $schedulePKey, $userPKey, 1)";
      $insertSQL = mysqli_query($conn, $insertQuery) or die(mysqli_error($conn));

    }

    echo "success";
  }

}

?>
