<?
    function disp($a){        
        echo $a;
    }
    
    //CRUD
    //part - Diary 
    function readDiarylist(){

    }

    function readDiary($uri){        
        switch($uri[2]){
            case 'diary':{
                echo 'readdiary';
                break;
            }

            case 'shop':{

                break;
            }
        }
    }

    function writeDiary($uri){
        switch($uri[2]){
            case 'diary':{
                echo 'writediary';
                $targetDir="/var/www/html/macarondiary/diaryimage/";
                $allowTypes = array('jpg','png','jpeg','gif');
                
                // $total = count($_FILES['imagefile']['name']);
                // echo $_FILES['imagefile']['name'];                
                // $imagehashmap = $_FILES['imagehashmap'];
                $imagefilearray = $_FILES;
                print_r($imagefilearray['0']);
                echo count($_FILES);
                
                // if (move_uploaded_file($_FILES['imagefile']['tmp_name'], $uploadfile)) {
                //     echo "File is valid, and was successfully uploaded.\n";
                // } else {
                //     echo "Possible file upload attack!\n";
                // }
                $statusMsg = $errorMsg = $errorUpload = $errorUploadType = '';
                foreach($_FILES as $key=>$val){ 
                    // File upload path 
                    $fileName = basename($_FILES[$key]['name']);
                    
                    $targetFilePath = $targetDir . $fileName; 
                     
                    // Check whether file type is valid 
                    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
                    if(in_array($fileType, $allowTypes)){ 
                        // Upload file to server 
                        if(move_uploaded_file($_FILES[$key]['tmp_name'], $targetFilePath)){ 
                            // Image db insert sql 
                            echo 'success';
                        }else{ 
                            $errorUpload .= $_FILES['tmp_name'][$key].' | ';
                            echo 'fail'; 
                        } 
                    }else{ 
                        $errorUploadType .= $_FILES['tmp_name'][$key].' | '; 
                    } 
                }
                // if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $file_path)) {
                //     $result =array("result" => "success", "value" => $var);
                // } else{
                //     $result = array("result" => "error");
                // }
                // echo json_encode($result);            
                $reqBody = $_POST['diaryhashmap'];                               
                $diaryJSON = json_decode($reqBody);
                $diary_title = $diaryJSON->{'diarytitle'};//리뷰 작성 날짜
                $diary_content = $diaryJSON->{'diarycontent'};//리뷰 내용
                $diary_macaronshop = $diaryJSON->{'diaryshopname'};//리뷰 해당 가게명
                $diary_date = $diaryJSON->{'diarydate'};//리뷰 작성 날짜
                
                echo json_encode(array('diarytitle' => $diary_title, 'diarycontent' => $diary_content , 'diaryshopname'=> $diary_macaronshop, 'diarydate' => $diary_date),JSON_UNESCAPED_UNICODE);
                // $_FILES["fileToUpload"]["name"];
                
                break;
            }

            case 'shop':{

                break;
            }
        }        
    }

    function updateDiary(){

    }

    function deleteDiary(){

    }

    //part - Shop 
    function readShoplist(){

    }

    function readShopinfo(){

    }
    
?>