<?
error_reporting(E_ALL);
ini_set("display_errors", 1);
header('Content-Type: application/json,multipart/form-data; charset=UTF-8');
header('HTTP/1.1 200 OK');
header('Access-Control-Allow-Methods: GET,POST,PUT,DELETE');    
$time = date("T-m-d H:i");    


//Connecting to DB
function conn_db(){
    try {
        $host="localhost";
        $db="macarondiary";
        $user="root";
        $password="2511";
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $password);
        $pdo->exec("set names utf8");
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
        // echo "데이터베이스 연결 성공!!<br/>";
    }catch(PDOException $e) {
        echo $e->getMessage();
    }
}

//JSON Check
function isJson($string) {
    json_decode($string);
    return (json_last_error() == JSON_ERROR_NONE);
}


?>