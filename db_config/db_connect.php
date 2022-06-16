<?php
// соединение с DB

define("HOST", 'localhost');
define("USER", 'root');
define("PASS", 'root');
define("DB_NAME", 'skynet_service');

$conn = mysqli_connect(HOST, USER, PASS, DB_NAME);

if(!$conn){
    echo "Error DB connect ";
    echo "<br>";
}