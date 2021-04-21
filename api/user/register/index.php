<?php
/**
* This API is for user register
* @api          {post} your_host/api/user/register
* @author       fan
* @param        {json / x-www-form-unlencoded}
* @param        POST {username, password}
* @return       json
* @version      1.0.0 
*/


/* 
*   include database configuration
*/
include("../../config.php");

/* 
*   Headers
*/
// Allow other host to visit
header('Access-Control-Allow-Origin:*');
// Resource media type
header('Content-type:application.json');

if($_SERVER['REQUEST_METHOD'] == 'POST'){
  $uname = $_POST['username'];
  $password = $_POST['password'];
  /* 
  *   check username exist
  */
  $result = $conn -> query("SELECT * FROM `users` WHERE `uname` = '$uname' ");
  $user = mysqli_fetch_assoc($result);
  if($user){
    $res['code'] = 207;
    $res['message'] = "register failed, user exist";
  }else{
    /* 
    *   insert into DB
    */
    $result = $conn -> query(
      "INSERT INTO `users`(`uname`,`password`) 
       VALUES ('$uname','$password')");
    if($result){
      $res['code'] = 200;
      $res['message'] = 'register successful';
    } else{
      $res['code'] = 400;
      $res['message'] = 'register failed';
    }
  }
}

$conn -> close();
echo json_encode($res);
die();