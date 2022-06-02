<?php
require_once 'get_token.php';

$shop_id = $_POST['shop_id'];
$sales_number = trim($_POST['sales_number']);

if ($sales_number == '') {
    echo 0;
} else {
    $sales_prefix = '';
    if ($shop_id == '5e113e2c7110be1850679bc4') {
        $sales_prefix = 'СП';
    } elseif ($shop_id == '5ec555833d33de1a02017aac') {
        $sales_prefix = 'РП';
    } elseif ($shop_id == '5ec7def8a5f389596ecbffb4') {
        $sales_prefix = 'ШП';
    }
    $params_sales = array(
        'filter' => $sales_prefix . $sales_number,
        'sort' => 'number DESC',
    );

    // получаем данные по мастерской
    $result_shops_sales = file_get_contents("https://api.livesklad.com/shops/" . $shop_id . "/sales", false, stream_context_create(array(
        'http' => array(
            'method'  => 'GET',
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n"
                . "Authorization: " . $TOKEN,
            'content' => http_build_query($params_sales),
        )
    )));



    echo $result_shops_sales;
}
