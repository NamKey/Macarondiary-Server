<?
    $host="localhost";
    $db="macarondiary";
    $user="root";
    $pass="2511";
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    header('Content-Type: application/json; charset=UTF-8');
    header('HTTP/1.1 200 OK');
    header('Access-Control-Allow-Methods: GET,POST,PUT,DELETE');
    $time = date("T-m-d H:i");
?>