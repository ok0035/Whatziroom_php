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

  $selectCheckUser = "select * from UserRoomList where UserKey = $userPKey
                      and RoomKey = $roomPKey and (UserRoomList.Status = 0 or UserRoomList.Status = 1 or UserRoomList.Status = 2);";

  $checkUserSQL = mysqli_query($conn, $selectCheckUser);
  $userNumber = mysqli_num_rows($checkUserSQL);

  if($userNumber >= 1) echo "already exist";

  else {

    //1은 신청중
    $insertRequestQuery = "insert into UserRoomList values(null, $userPKey, $roomPKey, now(), now(), 1, 0,0,0);";
    $insertRequestSQL = mysqli_query($conn, $insertRequestQuery) or die(mysqli_error($conn));


    /// fcm push notification start ///
    //1. 해당하는 유저의 토큰을 가져온다.//
    $getFBToken_query = "SELECT FirebaseToken FROM User, UserRoomList
    WHERE User.PKey = UserRoomList.UserKey
    AND UserRoomList.RoomKey = $roomPKey
    AND UserRoomList.Status Not In(1,4);";
    $getFBToken_sql = mysqli_query($conn,$getFBToken_query);
    $getFBToken_result = array();
    while($row = mysqli_fetch_assoc($getFBToken_sql)){
            $getFBToken_result[] = $row["FirebaseToken"];
          }
    send_notification($getFBToken_result);


    echo "success";


  }

}

mysqli_close($conn);


function send_notification($tokens)
{
  // echo 'start_notification';
  $url = 'https://fcm.googleapis.com/fcm/send';
  $fields = array(
     'registration_ids' => $tokens,
     'data' => array('body'=>"RequestEnterRoom")
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
