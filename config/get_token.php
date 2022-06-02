<?php
 // получение токена через пост
 $livesklad_auth = 'https://api.livesklad.com/auth';
 $params_auth = array(
     'login' => 'ed5e42cb8adb9aa38aef6de3861b2309', // в http://localhost/post.php это будет $_POST['param1'] == '123'
     'password' => 'wPNU5ZbZvh27dBQDS6Dy', // в http://localhost/post.php это будет $_POST['param2'] == 'abc'
 );
 $result_auth = file_get_contents($livesklad_auth, false, stream_context_create(array(
     'http' => array(
         'method'  => 'POST',
         'header'  => 'Content-type: application/x-www-form-urlencoded',
         'content' => http_build_query($params_auth)
     )
 )));

 $ansver_auth = json_decode($result_auth, true);

 // echo '<pre>';

 // print_r($ansver_auth);

 // echo '<br>';

 $TOKEN =  $ansver_auth['token'];