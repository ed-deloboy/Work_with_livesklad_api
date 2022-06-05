<?php
require_once 'get_token.php';

$agent_name = trim($_POST['agent_name']);

if ($agent_name == '') {
    echo 0;
} else {
    $params_agents = array(
        'filter' => $agent_name,
        'pageSize' => '50',
    );

    // получаем данные по мастерской
    $result_agent = file_get_contents("https://api.livesklad.com/counteragents", false, stream_context_create(array(
        'http' => array(
            'method'  => 'GET',
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n"
                . "Authorization: " . $TOKEN,
            'content' => http_build_query($params_agents),
        )
    )));


}
echo $result_agent;