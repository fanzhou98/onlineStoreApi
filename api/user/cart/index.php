<?php
/**
* This API is for user cart
* @api          {get / post} your_host/api/user/cart
* @author       fan
* @param        {json / x-www-form-unlencoded} 
* @param        GET {action: getCartList / delete, ...}
* @param        POST {action: addGoods / updateGoods,  ...}
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
*   GET
*/
if($_SERVER['REQUEST_METHOD'] == 'GET'){
  if($_GET['action']){
    $action = $_GET['action'];
  }else {
    $conn -> close();
    $res['code'] = 400;
    $res['message'] = 'need param action';
    echo json_encode($res);
    die();
  }
  
  /* 
  *   getCartList
  */
  if($action = 'getCartList'){
    $uid = $_GET['uid'];
    // sql statement
    $result = $conn -> query("SELECT * FROM `cart`,`goods` WHERE cart.goods_id = goods.goods_id AND`uid` = '$uid' ");
    if($result){
      $cartGoodsList = array();
      while($row = mysqli_fetch_assoc($result)) {
        array_push($cartGoodsList, $row);
      }
      $res['message'] = 'load successful';
      $res['code'] = 200;
      $res['cartGoodsList'] = $cartGoodsList;
    }else {
      $res['code'] = 400;
      $res['message'] = 'loading failed';
    }
  }

  /* 
  *   delete
  */
  if($action = 'delete'){
    $uid = $_GET['uid'];
    $item_id = $_GET['item_id'];
    // sql statement
    $result = $conn -> query("DELETE FROM `cart` WHERE `item_id` = '$item_id' AND `uid` = '$uid' ");
    if($result){
      $res['message'] = 'delete successful';
      $res['code'] = 200;
      $res['cartGoodsList'] = $cartGoodsList;
    }else {
      $res['code'] = 400;
      $res['message'] = 'delete failed';
    }
  }
}


 /* 
  *   POST
  */
if($_SERVER['REQUEST_METHOD'] == 'POST'){
 
  //  define action
  if($_POST['action']){
    $action = $_POST['action'];
  }else {
    $conn -> close();
    $res['code'] = 400;
    $res['message'] = 'need param action';
    echo json_encode($res);
    die();
  }

  /* 
  *   addGoods
  */
  if($action == 'addGoods'){
    $action = $_POST['action'];  
    $uid = $_POST['goodsItem'][uid];
    $goods_id =  $_POST['goodsItem'][goods_id];
    $price = $_POST['goodsItem'][price];
    $total_price = $_POST['goodsItem'][totalPrice];
    $item_number = $_POST['goodsItem'][itemNumber];

    // check wether the goods already in the cart
    $result = $conn -> query("SELECT `item_id`,`number`,`total_price` FROM `cart` WHERE `uid` = '$uid' AND`goods_id` = '$goods_id'");
    
    // request success
    if($result){ 
      $exist_info = [];
      while($row = mysqli_fetch_assoc($result)){
        array_push($exist_info, $row); 
      }
      // goods not in the cart
      if(count($exist_info) == 0){ 
        $req = $conn -> query(
          "INSERT INTO `cart`(`uid`,`goods_id`,`number`,`price`,`total_price`) 
           VALUES ('$uid', '$goods_id', '$item_number', '$price', '$total_price')");
        if($req){
          $res['code'] = 200;
          $res['result'] = 'add successful';
        }else{
          $res['item'] = $_POST['goodsItem'];
          $res['error'] = 'true';
          $res['result'] = 'add failed';
        }

        // goods already in the cart
      }else{ 
          // retrieve exist goods information
          $e_id = $exist_info[0][item_id];
          $e_number = $exist_info[0][number];
          $e_total_price = $exist_info[0][total_price];      
          // set new price and number
          $new_number = $item_number + $e_number;
          $new_price = $total_price + $e_total_price;
          // insert into database
          $req = $conn -> query("UPDATE `cart` SET `number`='$new_number',`total_price`='$new_price' WHERE `item_id` = '$e_id'");
          // insert success
          if($req){ 
            $res['res'] = $e_number;
            $res['code'] = 200;
            $res['result'] = 'update goods already in the cart';
          } else{ // insert failed
            $res['code'] = 400;
            $res['error'] = 'true';
            $res['result'] = 'add failed';
          }
        }
    } else { // if request $result failed
        $res['code'] = 400;
        $res['error'] = 'true';
        $res['result'] = 'add failed';
      }
  }

  /* 
  *   updateGoods
  */
  if($action == 'updateGoods'){
    $uid = $_POST['uid'];
    $goods_id =  $_POST['goods_id'];
    $total_price = $_POST['total_price'];
    $number = $_POST['number'];
    $result = $conn -> query("UPDATE `cart` SET `number` = '$number', `total_price` = '$total_price' WHERE `uid`= '$uid' AND `goods_id` = '$goods_id' ");
    if($result){
      $res['code'] = 200;
      $res['result'] = 'update successful';
    }else{
      $res['code'] = 400;
      $res['error'] = 'true';
      $res['result'] = 'update failed';
    }
  }
}

$conn -> close();
echo json_encode($res);
die();