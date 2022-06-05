<?php

// ШП3063
// "611f9c62a1caa05bc47a366c" id агента
require_once 'get_token.php';

$user_name = $_POST['user_name'];

// id городов
// if ($shop_id == '5e113e2c7110be1850679bc4') {
//     $sales_prefix = 'СП';
// } elseif ($shop_id == '5ec555833d33de1a02017aac') {
//     $sales_prefix = 'РП';
// } elseif ($shop_id == '5ec7def8a5f389596ecbffb4') {
//     $sales_prefix = 'ШП';
// }


$params_sales = array(
    // 'filter' => 'шп3000',
    'sort' => 'number DESC',
    'pageSize' => '50',
);

// получаем данные по мастерской
$result_shops_sales = file_get_contents("https://api.livesklad.com/shops/5ec7def8a5f389596ecbffb4/sales", false, stream_context_create(array(
    'http' => array(
        'method'  => 'GET',
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n"
            . "Authorization: " . $TOKEN,
        'content' => http_build_query($params_sales),
    )
)));

echo $result_shops_sales;
