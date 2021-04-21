<?php
/**
* This API is for editing user profile
* @api          {post} your_host/api/user/prifile
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

/* 
*   POST
*/
if($_SERVER['REQUEST_METHOD'] == 'POST'){
  // retreive action
  if($_POST['action']){
    $action = $_POST['action'];
  }else {
    $res['error'] = 'true';
    $conn -> close();
    echo json_encode($res);
    die();
  }

  /* 
  *   action = editAccount
  */
  if($action == 'editAccount'){
    $uid = $_POST['uid'];
    $nickName = $_POST['nickName'];
    $shippingAddress = $_POST['shippingAddress'];
    $receiptAddress = $_POST['receiptAddress'];
    $country = $_POST['country'];
    $email = $_POST['email'];

    $result = $conn -> query(
      "UPDATE `users` 
       SET `nick_name` = '$nickName',
           `shipping_address` = '$shippingAddress',
           `receipt_address` ='$receiptAddress',
           `country` = '$country',
           `email` ='$email'
       WHERE `uid` = '$uid'");
    $res['data'] = $country;
    if($result){
      $res['code'] = 200;
      $res['message'] = 'edit successful';
    }else{
      $res['code'] = 400;
      $res['error'] = 'true';
      $res['message'] = 'edit failed';
    }
  }
  
  /* 
  *   action = changePassword
  */
  elseif($action == 'changePassword'){
    $uid = $_POST['uid'];
    $new_pass = $_POST['password'];
    $result = $conn -> query("UPDATE `users` SET `password`='$new_pass' WHERE `uid` = '$uid'");
    if($result){
      $res['code'] = 200;
      $res['message'] = 'update password successfully';
    }else{
     $res['code'] = 400;
     $res['message'] = 'update password failed';
    }
  }
  
  /* 
  *   action = changePhoto
  */
  elseif($action == 'changePhoto'){

  }
  
  /* 
  *   action = deleteAccount
  */
  elseif($action == 'deleteAccount'){
    $uid = $_POST['uid'];
    $password = $_POST['password'];
    $result = $conn -> query("DELETE FROM `users` WHERE `uid` = '$uid' AND `password` = '$password'");
    if($result){
      $res['code'] = 200;
      $res['message'] = 'delete successful';
    }else {
      $res['code'] = 400;
      $res['error'] = 'delete failed';
    } 
  }
}

/* 
*   GET
*/
if($_SERVER['REQUEST_METHOD'] == 'GET'){
  // retreive action
  if($_GET['action']){
    $action = $_GET['action'];
  }else {
    $res['error'] = 'true';
    $conn -> close();
    echo json_encode($res);
    die();
  }

  if($action == "getUserInfo"){
    $uid = $_GET['uid'];
    $result = $conn -> query("SELECT * FROM `users` WHERE `uid` = '$uid' ");
    if($result){
      $user = array();
      while($row = mysqli_fetch_assoc($result)){
        array_push($user, $row);
      }
      $res['code'] = 200;
      $res['userInfo'] = $user;
    }else {
      $res['code'] = 400;
      $res['error'] = 'true';
    }
  }
}


$conn -> close();
echo json_encode($res);
die();


