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
$roomName = isset($_POST["RoomName"]) ? $_POST["RoomName"] : "";

if($userPKey == 0 || $roomPKey == 0 ) echo "fail_PKey";

else {
  //수락할 인원이 있는지 확인( 혹시 모르니까.. )
  $checkUserQuery = "select UserKey from UserRoomList where UserKey = $userPKey and RoomKey = $roomPKey and Status = 1";
  $checkUserSQL = mysqli_query($conn, $checkUserQuery) or die(mysqli_error($conn));
  $checkUser = mysqli_num_rows($checkUserSQL);

  //신청중인 인원이 있다면 Status를 2로 바꿔준다.
  if($checkUser > 0) {

    $updateStatusQuery = "update UserRoomList set Status = 2 where UserKey = $userPKey and RoomKey = $roomPKey and Status = 1";
    $updateStatusSQL = mysqli_query($conn, $updateStatusQuery) or die(mysqli_error($conn));

    $getFBToken_query = "SELECT distinct FirebaseToken FROM User
    Where PKey = $userPKey";
    $getFBToken_sql = mysqli_query($conn,$getFBToken_query);
    $getFBToken_result = array();
    while($row = mysqli_fetch_assoc($getFBToken_sql)){
            $getFBToken_result[] = $row["FirebaseToken"];
          }

    send_notification($getFBToken_result, $roomName);

    echo "success";

  } else echo "fail"; // 신청중인 인원이 없다는 뜻이므로 만약 이곳에 오면 클라이언트에는 리스트가 존재하는데 서버에는 존재하지 않는 경우이다.
}

mysqli_close($conn);


function send_notification($tokens, $roomName)
{
  // echo 'start_notification';
  $url = 'https://fcm.googleapis.com/fcm/send';
  $fields = array(
     'registration_ids' => $tokens,
     'data' => array('body'=>"AcceptEnterRoom", 'roomName'=>$roomName)
    );

  $headers = array(
    'Authorization:key ='.'AAAAsAdVIqw:APA91bHOUA5PH3OOJZl63KE8GXoJcH6T7WUS9d0c0sie6idi7CGcgX2204-KQIAJsppUXtkglfrr64_UGgP-ZbHizhLtB6H7XrMhVujtFZVU0eTmueUScdH0ijtoJznOiDL6ZsrDBuHV',
    'Content-Type: application/json'
    );

   $ch = curl_init();
     curl_setopt($ch, CURLOPT_URL, $url);
     curl_setopt($ch, CURLOPT_POST, true);
     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
     curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
     curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
     $result = curl_exec($ch);
     if ($result === FALSE) {
         die('Curl failed: ' . curl_error($ch));
     }
     curl_close($ch);
     return $result;
}
?>
