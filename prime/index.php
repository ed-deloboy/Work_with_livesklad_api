<!-- https://api.livesklad.com/auth -->

<?php

// получение токена через пост
$url_auth = 'https://api.livesklad.com/auth';
$params_auth = array(
    'login' => 'ed5e42cb8adb9aa38aef6de3861b2309', // в http://localhost/post.php это будет $_POST['param1'] == '123'
    'password' => 'wPNU5ZbZvh27dBQDS6Dy', // в http://localhost/post.php это будет $_POST['param2'] == 'abc'
);
$result_auth = file_get_contents($url_auth, false, stream_context_create(array(
    'http' => array(
        'method'  => 'POST',
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'content' => http_build_query($params_auth)
    )
)));

$ansver_auth = json_decode($result_auth, true);

echo '<pre>';

print_r($ansver_auth);

echo '<br>';

$TOKEN =  $ansver_auth['token'];


// В случае успешной авторизации в ответ придет токен, он будет действителен в течение 15 минут, данный токен должен передаваться в каждом запросе в заголовке:
// Authorization: <token>

// $request_params = [
//     'Authorization' => $TOKEN,
//     ];

// $url = "https://api.livesklad.com/shops?" .http_build_query($request_params);
//Разкоментируйте строчку ниже если хотите отправить запрос со своего OpenServer или просто введите в 
//строку браузера после получения var_damp еще добавьте https если у вашего хостинга есть 
//SSL сертификат иначе не отправится запрос УДАЧИ!

// $result_shops = file_get_contents("https://api.livesklad.com/shops?" .$TOKEN, true);
// $params_shops = array(
  
// );
$result_shops = file_get_contents("https://api.livesklad.com/shops/5e113e2c7110be1850679bc4/orders", false, stream_context_create(array(
    'http' => array(
        'method'  => 'GET',
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n" 
        . "Authorization: ".$TOKEN,
        // 'content' => http_build_query($params_shops)

    )
)));

$shops_arr = json_decode($result_shops, true);

echo '<pre>';
echo '<br>';
echo '<Контент json = >';
print_r($shops_arr);







// $shops_content = 'https://api.livesklad.com/shops?'.$TOKEN;

// $result_shops = file_get_contents($shops_content, true);

// $shops_arr = json_decode($result, true);

// echo '<pre>';
// echo '<br>';
// echo '<Контент шопс = >';

// var_dump($shops_arr);



?>