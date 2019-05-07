<?php 
include('connect.php'); //include connect.php

$param = $_GET['param']; //ambil parameter dari ajax di event chart drilldown

$conn = new Connect(); //init connect

$test = $conn->getTableColumnNames('sample_data');

$data = array();

foreach ($test as $key => $value) {

    $data[$key] = $value['field'];

}

echo json_encode($data, JSON_NUMERIC_CHECK);


