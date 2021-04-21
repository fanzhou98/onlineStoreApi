<?php
/**
* This config used for configure the database connection
* @author       fan
* @version      1.0.0 
*/

// connect to database
$conn = new mysqli('localhost', 'root', 'root','mydevstore');
if ($conn -> connect_error){
  die("Oops, Could not connect to database");
}
$conn -> query('set names utf-8');
$res = array('error' => false);