<?php

// include_once("../DBConnector.php");
include_once $_SERVER["DOCUMENT_ROOT"]."/whatziroom/DB/DBConnector.php";
$classDB = new ClassDB();
$conn=$classDB->getConn();
if(!$conn){
  die();
}

if(isset($_POST["UserPKey"]) && isset($_POST["FriendKey"]) && isset($_POST["Status"]) ){
  $UserPKey = $_POST["UserPKey"];
  $FriendPKey = $_POST["FriendKey"];
  $Status = $_POST["Status"];
}else{
  echo "POST_ERROR";
}


//fcm GOOGLE_API_KEY 등록
define("GOOGLE_API_KEY", "AAAAsAdVIqw:APA91bHOUA5PH3OOJZl63KE8GXoJcH6T7WUS9d0c0sie6idi7CGcgX2204-KQIAJsppUXtkglfrr64_UGgP-ZbHizhLtB6H7XrMhVujtFZVU0eTmueUScdH0ijtoJznOiDL6ZsrDBuHV");

/// fcm push notification start ///
//1. 해당하는 유저의 토큰을 가져온다.//
$getFBToken_query = "SELECT FirebaseToken FROM User WHERE PKey = $FriendPKey;";
$getFBToken_sql = mysqli_query($conn,$getFBToken_query);
$getFBToken_result = array();
while($row = mysqli_fetch_assoc($getFBToken_sql)){
        $getFBToken_result[] = $row["FirebaseToken"];
      }


// $FBToken_row = mysqli_fetch_array($getFBToken_sql);
// $FBToken = $FBToken_row['FirebaseToken'];

$insertFriend_query = "INSERT INTO Friend(UserPKey, FriendKey, Status, CreatedDate, UpdatedDate) VALUES ($UserPKey, $FriendPKey, $Status, Now(), Now());";
$insertFriend_sql = mysqli_query($conn,$insertFriend_query);

if($insertFriend_sql){

  $message_status = send_notification($getFBToken_result);
  // echo $message_status;
  echo "INSERT_FRIEND_SUCCESS";
}else{
  echo $insertFriend_query;
}

mysqli_close($conn);


function send_notification($tokens)
{
  // echo 'start_notification';
  $url = 'https://fcm.googleapis.com/fcm/send';
  $fields = array(
     'registration_ids' => $tokens,
     'data' => array('body'=>"RequestFriend")
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
