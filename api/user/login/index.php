<?php
/**
* This API is for user login
* @api          {post} your_host/api/user/login
* @author       fan
* @param        {json / x-www-form-unlencoded} 
* @param        POST {username, password}
* @return       json
* @version      1.0.0 
*/


/* 
*   include database configuration -> $conn , $res
*/
include("../../config.php");

/* 
*   Headers
*/
// Allow other host to visit
header('Access-Control-Allow-Origin:*');
// Resource media type
header('Content-type:application.json');

/* 
* POST
*/
if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $uname = $_POST['username'];
  $password = $_POST['password'];

  // sql statement
  $result = $conn -> query("SELECT * FROM `users` WHERE `uname` = '$uname' AND `password` = '$password' ");
  $user = array();
  while($row = $result -> fetch_assoc()) {
    array_push($user, $row);
  }
  if($user){
    $res['code'] = 200;
    $res['user'] = $user;
    $res['message'] = "login successful";
  }else {
    $res['code'] = 400;
    $res['message'] = "login failed";
  }
}

$conn -> close();
echo json_encode($res);
die();