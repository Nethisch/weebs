<?php

include('connect.php');//konek

$conn = new Connect();//obj baru

$method = $_SERVER['REQUEST_METHOD'];// method lemparan dari controller grid table

$param = array(); //init array kosong

if($method == 'GET'){

if(!empty($_GET) && isset($_GET)){

  $param = array('gender' => $_GET['param']);//data dari drilldown chart
    
  $result = $conn->gridData($method,$param);//lempar data ke function gridData di connect.php dengan parameter method GET

}

 foreach($result as $row)//masukin data balikan data dari gridData di connect.php
 {
  $output[] = array(
   'id'    => $row['id'],   
   'first_name'  => $row['first_name'],
   'last_name'   => $row['last_name'],
   'age'    => $row['age'],
   'gender'   => $row['gender']
  );
 }
 header("Content-Type: application/json");
 echo json_encode($output);//data yang udah jadi dilempar balik ke gridTable di index.php 
}

if($method == "POST")
{

    $param['first_name'] = $_POST['first_name'];
    $param['last_name'] = $_POST['last_name'];
    $param['age'] = $_POST['age'];
    $param['gender'] = $_POST['gender'];

    $conn->gridData($method,$param);//lempar data ke function gridData di connect.php dengan parameter method POST
}

if($method == 'PUT')
{
 parse_str(file_get_contents("php://input"), $_PUT);

    $param['first_name'] = $_PUT['first_name'];
    $param['last_name'] = $_PUT['last_name'];
    $param['age'] = $_PUT['age'];
    $param['gender'] = $_PUT['gender'];
    $param['id'] = $_PUT['id'];
    
    $conn->gridData($method,$param);//lempar data ke function gridData di connect.php dengan parameter method PUT

}

if($method == "DELETE")
{
 parse_str(file_get_contents("php://input"), $_DELETE);

 $param['id'] = $_DELETE['id'];

 $conn->gridData($method,$param);//lempar data ke function gridData di connect.php dengan parameter method DELETE
}