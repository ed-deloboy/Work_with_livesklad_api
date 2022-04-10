<?php

require_once 'connect.php';


// https://api.vk.com/method/friends.getSuggestions?&access_token=&v=5.120

$auth = 'https://api.livesklad.com/counteragents?dateCreateFrom&token=XWsRqkyjNK7JRT47tii30oVE6B2g08r';

$response_json = file_get_contents($auth);
$ansver_arr = json_decode($response_json, true);

echo '<pre>';
echo "<br>";
print_r($ansver_arr);