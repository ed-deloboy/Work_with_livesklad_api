<?php

// ШП3063
// "611f9c62a1caa05bc47a366c" id агента
require_once 'get_token.php';

$user_name = $_POST['user_name'];
$city_buy = $_POST['city_buy'];
$city_id = 0;
$sales_prefix = '';
// проверка префикса
if ($city_buy == 1) {
    $city_id = '5ec555833d33de1a02017aac';
    $sales_prefix = 'РП';
} elseif ($city_buy == 2) {
    $city_id = '5ec7def8a5f389596ecbffb4';
    $sales_prefix = 'ШП';
} elseif ($city_buy == 3) {
    $city_id = '5e113e2c7110be1850679bc4';
    $sales_prefix = 'СП';
}

// id городов
// if ($city_id == '5e113e2c7110be1850679bc4') {
//     $sales_prefix = 'СП';
// } elseif ($city_id == '5ec555833d33de1a02017aac') {
//     $sales_prefix = 'РП';
// } elseif ($city_id == '5ec7def8a5f389596ecbffb4') {
//     $sales_prefix = 'ШП';
// }
$first_sales_array = array();

for ($i = 1; $i < 6;) {

    $request_sales = array(
        'sort' => 'date DESC',
        'pageSize' => '50',
        'page' => $i,
    );

    // получаем данные по мастерской
    $result_shops_sales = file_get_contents("https://api.livesklad.com/shops/" . $city_id . "/sales", false, stream_context_create(array(
        // $result_shops_sales = file_get_contents("https://api.livesklad.com/shops/5e113e2c7110be1850679bc4/sales", false, stream_context_create(array(
        'http' => array(
            'method'  => 'GET',
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n"
                . "Authorization: " . $TOKEN,
            'content' => http_build_query($request_sales),
        )
    )));
    $first_sales_array[] = json_decode($result_shops_sales, true);
    sleep(1);
}


echo $first_sales_array;
exit();

$shop_sales_id = array();

for ($id = 0; $id < count($shop_sales_50['data']); $id++) {
    $shop_sales_id[] = $shop_sales_50['data'][$id]['id'];
}


$docs_sales_arr = array();

for ($i = 0; $i < count($shop_sales_id); $i++) {
    // получаем данные продажи по id 609cf4c8715a9a0b441da522
    // $result_docs_sales = file_get_contents("https://api.livesklad.com/documents/" . $shop_sales_50['data'][$i]['id'], false, stream_context_create(array(
    $result_docs_sales = file_get_contents("https://api.livesklad.com/documents/" . $shop_sales_id[$i], false, stream_context_create(array(
        'http' => array(
            'method'  => 'GET',
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n"
                . "Authorization: " . $TOKEN,
        )
    )));

    $docs_sales_arr[] = json_decode($result_docs_sales, true);
    sleep(1);
}

// выдаем на фронт
// echo $result_docs_sales;
echo json_encode($docs_sales_arr);
// echo '<pre>';
// print_r($docs_sales_arr);
