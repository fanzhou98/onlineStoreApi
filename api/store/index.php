<?php
/**
* This API is for store goods display
* @api          {get / post} your_host/api/user/store
* @author       fan
* @param        {json / x-www-form-unlencoded} 
* @param        GET {action: read}
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

if($_SERVER['REQUEST_METHOD'] === 'GET'){
  // request goosList
  $result = $conn -> query("SELECT * FROM `goods`");
  $goodsList = array();
  if($result){
    while($row = $result -> fetch_assoc()) {
      array_push($goodsList, $row);
    }
    $res['goodsList'] = $goodsList;
  }

  // request category list
  $category = $conn -> query("SELECT DISTINCT category FROM goods");
  $cat = array();
  if($category){
    while($row = mysqli_fetch_assoc($category)){
      array_push($cat, $row);
    }
    $res['catList'] = $cat;
  }
}

$conn -> close();
echo json_encode($res);
die();