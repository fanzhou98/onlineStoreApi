<?php
/**
* This API is for user browser history
* @api          {post} your_host/api/user/history
* @author       fan
* @param        {json / x-www-form-unlencoded} 
* @param        POST {action: read / save / delete,  ...}
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


if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $action = $_POST['action'];
  $uid = $_POST['uid'];
  $goods_id = $_POST['goods_id'];

  /* 
  *   read
  */
  if($action == 'read'){
    $result = $conn -> query("SELECT * FROM `browser_history`,`goods` WHERE browser_history.uid = $uid AND browser_history.goods_id = goods.goods_id ORDER BY browser_history.history_id desc ");
    $history_item = array();
    if($result){
      while($row = mysqli_fetch_assoc($result)){
        array_push($history_item, $row);
      }
      $res['history_item'] = $history_item;
      $res['message'] = 'read successful';
      $res['code'] = 200;
    }else{
      $res['message'] = 'read failed';
      $res['code'] = 400;
    }
  }

  /* 
  *   save 
  */
  if($action == 'save'){
    // delete existed history goods
    $req = $conn -> query("DELETE FROM `browser_history` WHERE browser_history.uid = $uid AND browser_history.goods_id = $goods_id");
    // insert with new id
    $result = $conn -> query("INSERT INTO `browser_history` (`goods_id`, `uid`) VALUES ('$goods_id', '$uid') ");
    if($result){
      $res['message'] = 'save successful';
      $res['code'] = 200;
    }else{
      $res['message'] = 'save failed';
      $res['code'] = 400;
    }
  }

  /* 
  *    delete
  */
  if($action == 'delete'){
    // delete existed history goods
    $result = $conn -> query("DELETE FROM `browser_history` WHERE browser_history.uid = $uid AND browser_history.goods_id = $goods_id");
    // insert with new id
    if($result){
      $res['message'] = 'save successful';
      $res['code'] = 200;
    }else{
      $res['message'] = 'save failed';
      $res['code'] = 400;
    }
  }
}

$conn -> close();
echo json_encode($res);
die();

