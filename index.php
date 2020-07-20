<?
    
    include './db_connection.php'; //DB Connection setting 
    include './fun_macarondiary.php'; //Global function
    // ini_set('display_errors',1);
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
        switch($reqMeth){
            case 'GET':{                                
                // /uri[1]       /uri[2]/uri[3]/uri[4]
                // /api name     /category/userid
                // EXAMPLE
                // /macarondiary/diary/macaron/

                if(empty($uri[4])){
                    readDiarylist($uri);
                }else{
                // EXAMPLE
                // /macarondiary/diary/macaron/1
                    readDiary($uri);
                }        
                break;
            }
            case 'POST':{
                writeDiary($uri);
                break;
            }
            case 'PUT':{
                
                break;
            }
            case 'DELETE':{
                break;
            }            
        }
    }else{
        header("HTTP/1.1 405 Wrong Request Method");
        exit();
    }   
?>
