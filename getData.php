<?php 
include('connect.php'); //include connect.php

$param = $_GET['param']; //ambil parameter dari ajax di event chart drilldown

$conn = new Connect(); //init connect

$get = $conn->getData($param);//function getData di connect.php, sambil lempar param

$data = array(); //ini array kosong

if(!empty($param)){//jika var param tidak kosong, artinya sudah ke drlldown, lakukan ini

    $stmt = explode('-',$param);//pecah string jadi array

    $searcher = $stmt[0];//data kolom yang mau di search

    $searchData = $stmt[1];//data yang mau di search

    if(!empty($searcher) && isset($searcher)){//jika data kolom tidak kosong lakukan ini

        foreach ($get as $key => $value) {
            $data['id'] = $searchData;
            $data['name'] = 'Test Data Drilldown2';
            $data['data'][$key]['name'] = $value['first_name'];
            $data['data'][$key]['y'] = $value['age'];
        }
    
    }else{//klo kosong yang ini
        foreach ($get as $key => $value) {
            $data['name'] = 'Test Data Drilldown';
            $data['data'][$key]['name'] = $value['gender'];
            $data['data'][$key]['y'] = $value['jumlah'];
            $data['data'][$key]['drilldown'] = $value['gender'];
            $data['data'][$key]['drilldownSearcher'] = 'gender';
        }
    }

}else{//klo param kosong, lakukan ini. Param kosong berarti datanya belum ke drilldonw atau disebut juga data awal

    foreach ($get as $key => $value) {
        $data['name'] = 'Test Data';
        $data['data'][$key]['name'] = $value['gender'];
        $data['data'][$key]['y'] = $value['jumlah'];
        $data['data'][$key]['drilldown'] = $value['gender'];
        $data['data'][$key]['drilldownSearcher'] = '';
    }

}

echo json_encode($data, JSON_NUMERIC_CHECK);


