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
$ChatMsg =  isset($_POST["ChatMsg"]) ? $_POST["ChatMsg"] : "0";
$userName = isset($_POST["UserName"]) ? $_POST["UserName"] : "";
$roomName = isset($_POST["RoomName"]) ? $_POST["RoomName"] : "";

if($userPKey == 0 || $roomPKey == 0) echo "fail";
else {

  $getFBToken_query = "SELECT distinct FirebaseToken FROM User, UserRoomList WHERE
  User.PKey = UserRoomList.UserKey AND UserRoomList.RoomKey = $roomPKey
  AND UserRoomList.UserKey != $userPKey";
  $getFBToken_sql = mysqli_query($conn,$getFBToken_query);
  $getFBToken_result = array();
  while($row = mysqli_fetch_assoc($getFBToken_sql)){
          $getFBToken_result[] = $row["FirebaseToken"];
        }


  $message_status = send_notification($getFBToken_result, $ChatMsg, $userName, $roomName);


}

function send_notification($tokens, $msg, $sender, $roomName)
{
  // echo 'start_notification';
  $url = 'https://fcm.googleapis.com/fcm/send';
  $fields = array(
     'registration_ids' => $tokens,
     'data' => array('body'=>"ChatMsg", 'txtMsg' => $msg, 'sender' => $sender, 'roomName' => $roomName)
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

mysqli_close($conn);
?>
