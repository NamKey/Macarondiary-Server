<?
    function checkReqMethod($reqMeth){
        switch ($reqMeth){
            case 'GET':        
            case 'POST':
            case 'PUT':
            case 'DELETE':                
                return true;            
            break;

            default:                
                return false;
                
            break;
        }
    }
        
?>