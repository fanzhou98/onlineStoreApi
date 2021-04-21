<?php
/**
* This API is for order function
* @api          {get / post} your_host/api/order
* @author       fan
* @param        {json / x-www-form-unlencoded} 
* @param        POST {action: submitOrder}
* @param        GET {action: askOrderList, deleteOrder, getShippingTrack}
* @return       json
* @version      1.0.0 
*/

/* 
*   include database configuration -> $conn , $res
*/
include("../config.php");

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

  if($action == 'askOrderList'){
    $uid = $_GET['uid'];
    $result = $conn -> query("SELECT * FROM `order`, `goods` WHERE order.uid = '$uid' AND order.goods_id = goods.goods_id");
    $order_list = array();
    while($row = mysqli_fetch_assoc($result)){
      array_push($order_list, $row);
    }
    $res['code'] = 200;
    $res['orderList'] = $order_list;
    $res['message'] = 'get orderlist successfully';
  }

  if($action == 'deleteOrder'){
    $order_id = $_GET['order_id'];
    $result = $conn -> query("DELETE FROM `order` WHERE `order_id` = '$order_id'");
    $ress = $conn -> query("DELETE FROM `shipping_track` WHERE `order_id` = '$order_id'");
    if($result && $ress){
      $res['code'] = 200;
      $res['message'] = 'successfully delete order';
    }else {
      $res['code'] = 400;
      $res['message'] = 'failed delete order';
    }
  }

  if($action == 'getShippingTrack'){
    $uid = $_GET['uid'];
    $result = $conn -> query("SELECT * FROM `order`, `shipping_track` WHERE order.uid = '$uid' AND order.order_id = shipping_track.order_id");
    $ship_list = array();
    while($row = mysqli_fetch_assoc($result)){
      array_push($ship_list, $row);
    }
    $res['code'] = 200;
    $res['ship_list'] = $ship_list;
    $res['message'] = 'get orderlist successfully';
  }
}

/* 
*   POST
*/
if($_SERVER['REQUEST_METHOD'] == 'POST'){
  if($_POST['action']){
    $action = $_POST['action'];
  }else {
    $conn -> close();
    $res['code'] = 400;
    $res['message'] = 'need param action';
    echo json_encode($res);
    die();
  }

  if($action == 'submitOrder'){
    $uid = $_POST['uid'];
    $goods_id = $_POST['goods_id'];
    $goods_number = $_POST['goods_number'];
    $price = $_POST['price'];
    $total_price = $_POST['total_price'];
    $payment_method = $_POST['payment_method'];
    $shipping_address = $_POST['shipping_address'];
    $order_status = $_POST['order_status'];
    
    $result = $conn -> query(
      "INSERT INTO `order`(`uid`, `goods_id`, `goods_number`, `price`, `total_price`, `payment_method`, `shipping_address`, `order_status`)
       VALUES('$uid', '$goods_id', '$goods_number', '$price', '$total_price', '$payment_method', '$shipping_address','$order_status')");
    if($result){
      // get prikey of lask insert
      $order_id = mysqli_insert_id($conn); 
      // insert into shippingTrack
      $ress = $conn -> query(
        "INSERT INTO `shipping_track`(`order_id`,shipping_address)
         VALUES('$order_id','$shipping_address')"
      );
      $res['id'] = $order_id;
      $res['code'] = 200;
      $res['message'] = 'order created successfully';
    }else {
      $res['data'] = $order_status;
      $res['code'] = 400;
      $res['message'] = 'order created failed';
    }
  }
}

$conn -> close();
echo json_encode($res);
die();