<?php
require_once 'get_token.php';

echo '<pre>';

$agent_name = trim($_POST['agent_name']);


$params_agents = array(
    'sort' => 'id ASC',
    'pageSize' => '50',
    'page' => '1',
    'isBuyer' => true,
    'isVendor' => false,
);

// получаем данные по мастерской
$result_agent = file_get_contents("https://api.livesklad.com/counteragents/", false, stream_context_create(array(
    'http' => array(
        'method'  => 'GET',
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n"
            . "Authorization: " . $TOKEN,
        'content' => http_build_query($params_agents),
    )
)));

$data = json_decode($result_agent, true);
$ls_users = $data['data'];
$users_for_db = array();
echo $data['remainRequest'] . '<br>';

// print_r(json_decode($result_agent, true));

$db_user_id = mysqli_fetch_all(mysqli_query($conn, "SELECT user_id FROM users ORDER BY 'user_id' DESC LIMIT 50"));
for ($i = 0; $i < 50; $i++) {
    $position = 0;

    if ($ls_users[$i]['id'] != $db_user_id[$i][0]) {

        echo 'ID не совпали' . '<br>';
        echo 'Добавляем, в массив users_for_db id ' . $ls_users[$i]['id'] . '<br>';

        $ls_user_id = $ls_users[$i]['id'];
        $ls_user_category = $ls_users[$i]['customerFields']['text'];
        $ls_user_name = $ls_users[$i]['name'];
        $ls_user_phone = $ls_users[$i]['phones'][0];
        $ls_user_mail = $ls_users[$i]['email'];
        
        mysqli_query($conn, "INSERT INTO `users`(`id`, `user_id`, `user_category`, `user_name`, `user_phone`, `user_mail`) VALUES (NULL,'$ls_user_id','$ls_user_category','$ls_user_name','$ls_user_phone','$ls_user_mail')");
    } else {
        $position = 1;
        if ($position == 1) {
            continue;
        }
    }
}

// for ($i = 0; $i < count($users_for_db); $i++) {
//     echo $i . '<br>';
//     echo $users_for_db[$i] . '<br>';
// }
