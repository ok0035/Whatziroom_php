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

if($userPKey == 0 || $roomPKey == 0) echo "fail";

else {

  $selectCheckUser = "select Status from UserRoomList where UserKey = $userPKey and RoomKey = $roomPKey
                      and (UserRoomList.Status = 0 or UserRoomList.Status = 1 or UserRoomList.Status = 2);";

  $checkUserSQL = mysqli_query($conn, $selectCheckUser) or die(mysqli_error($conn));
  $checkUserNumber = mysqli_num_rows($checkUserSQL);

  //나갈 대상이 있는지 먼저 체크
  if($checkUserNumber > 0) {

    $status = mysqli_fetch_row($checkUserSQL)[0];

    //만약 나갈 대상이 방장인 경우 다른 사람에게 양도해주어야함
    if($status == 0) {

      //방장 다음으로 가입한 사람에게 자동으로 방장 양도
      $replaceMakerQuery = "select UserKey from UserRoomList where RoomKey = $roomPKey and Status = 2 LIMIT 1";
      $replaceMakerSQL = mysqli_query($conn, $replaceMakerQuery) or die(mysqli_error($conn));

      //양도할 대상이 있는지 체크
      $replaceMakerNumber = mysqli_num_rows($replaceMakerSQL);

      //양도할 대상이 있다면 양도를 시작한다.
      if($replaceMakerNumber > 0) {

        $replaceMakerPKey = mysqli_fetch_row($replaceMakerSQL)[0];

        //방장 양도
        $updateMakerQuery = "update UserRoomList set Status = 0 where UserKey = $replaceMakerPKey and RoomKey = $roomPKey and Status = 2";
        $updateMakerSQL = mysqli_query($conn, $updateMakerQuery) or die(mysqli_error($conn));

        //방장을 양도했으면 전주인은 Status를 4로 바꿔서 나감 처리 해준다.
        $updateOutMakerQuery = "update UserRoomList set Status = 4 where UserKey = $userPKey and RoomKey = $roomPKey and Status = 0";
        $updateOutMakerSQL = mysqli_query($conn, $updateOutMakerQuery) or die(mysqli_error($conn));

        echo "success";

      } else {

        //양도할 대상이 없다면 방을 나가고(UserRoomList Status = 4) 방을 비활성화 (Room Status = 1)로 바꿔준다.

        //우선 방장을 나감 처리한다.
        $updateOutMakerQuery = "update UserRoomList set Status = 4 where UserKey = $userPKey and RoomKey = $roomPKey and Status = 0";
        $updateOutMakerSQL = mysqli_query($conn, $updateOutMakerQuery) or die(mysqli_error($conn));

        //방장이 나간 빈방을 비활성화 처리해준다.
        $updateUnableRoomQuery = "update Room set Status = 1 where PKey = $roomPKey";
        $updateUnableRoomSQL = mysqli_query($conn, $updateUnableRoomQuery) or die(mysqli_error($conn));

        echo "success";

      }

    } else {

      //만약 방장이 아닌 경우 UserRoomList Status를 4로 바꿔서 나감 처리 해주면 끝.
      $updateOutUserQuery = "update UserRoomList set Status = 4 where UserKey = $userPKey and RoomKey = $roomPKey and (Status = 1 or Status = 2)";
      $updateOutUserSQL = mysqli_query($conn, $updateOutUserQuery) or die(mysqli_error($conn));

      echo "success";

    }

  }

}

?>
