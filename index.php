<?
    include './db_connection.php'; //DB Connection setting 
    include './fun_macarondiary.php'; //Global function
    
    $requri = $_SERVER['REQUEST_URI'];    
    $uri = explode('/',parse_url($requri,PHP_URL_PATH));    
    
    //Macarondiary URI Check
    if($uri[1] !== 'macarondiary'){
        header("HTTP/1.1 404 not Found error"); 
        exit();
    }
    
    //Request Method Check
    $reqMeth = $_SERVER['REQUEST_METHOD'];
    if(checkReqMethod($reqMeth)){
        require_once 'macaron_api.php';
    }else{
        header("HTTP/1.1 405 Wrong Request Method");
        exit();
    }

    //
    disp($uri[1]);
    disp($uri[2]);
    disp($uri[3]);
?>
