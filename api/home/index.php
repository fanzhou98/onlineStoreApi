<?php
/**
* This API is for Home page
* @api          {get / post} your_host/api/user/home
* @author       fan
* @param        {json / x-www-form-unlencoded} 
* @param        GET {action: load}
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
* GET
*/
if($_SERVER['REQUEST_METHOD'] === 'GET'){
  $action = $_GET['action'];

  /* 
  *   action = load
  */
  if($action == load){
    $result = $conn -> query("SELECT * FROM `home_slides`");
    $slides = array();
  
    if($result){
      while($row = $result -> fetch_assoc()) {
        array_push($slides, $row);
      }
      $res['slides'] = $slides;
      $res['message'] = "load successful";
    }else{
      $res['message'] = "load failed";
    }
  }
}

$conn -> close();
echo json_encode($res);
die();