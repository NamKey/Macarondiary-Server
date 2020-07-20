<?
    function disp($a){        
        echo $a;
    }
    
//CRUD
//part - Diary 
/*
Function readDiarylist($uri)
1. purpose : Diary Activity에 클라이언트가 들어갔을 때 전체적인 일기 목록을 보여주기 위해 필요한 부분
2. method : GET
   --Text
    1) diary table의 모든 컬럼, image path 를 json형식으로 보냄
    2) image path를 macarondiary에서 갖고 오기 위해 join / thumbnail만 갖고 오기 위해서 group by / 최신 추가 된 순서부터
    3) 
    --Image
    1) image path만 text와 함께 보냄
*/
    function readDiarylist($uri){
        switch($uri[2]){
            case 'diary':{                
                $userid = $uri[3];                
                // echo $userid;
                
                //DB Connect Setting with PDO
                $pdo = conn_db();
                
                //text sql 처리                
                $diarystmt = $pdo->prepare(
                "SELECT diary.*
                ,image_path 
                FROM diary AS diary 
                JOIN macaronimages AS images 
                ON diary.pk = images.image_key 
                WHERE diary.user_id='macaron' 
                GROUP BY diary.pk
                ORDER BY diary.pk desc;");

                //모든 일기들을 다 갖고 오는데 성공하면
                if($diarystmt->execute())
                {   
                    //diary list array                 
                    $diarylist=array();
                    //행으로 받아옴
                    $rows=$diarystmt->fetchAll();
                    // print_r(json_encode($rows));
                    foreach($rows as $result)
                    {
                        // $diaryresult->user_id=$result['user_id']; // 추후 ID 도입을 위한 부분
                        
                        $diaryresult= new StdClass();

                        //값들을 담아올 객체 생성 
                        //없을 시 Creating default object from empty value

                        $diaryresult->diaryid=$result['pk'];                        
                        $diaryresult->diarytitle=$result['diary_title'];
                        $diaryresult->diarycontent=$result['diary_content'];
                        $diaryresult->diaryshopname=$result['diary_shopname'];
                        $diaryresult->diarydate=$result['diary_date'];
                        $diaryresult->diarythumbnailpath=$result['image_path'];
                        // print_r($diaryresult);
                        // Client에서 List<Object>를 받으므로 $diaryresult를 json encoding하지 않고 보냄
                        array_push($diarylist,$diaryresult);//array에 데이터 추가
                    }
                    print_r(stripslashes(json_encode($diarylist,JSON_UNESCAPED_UNICODE)));//JSON으로 encoding하여 보냄
                    //["{\"diaryid\":1,\"diarytitle\":\"단짠단짠\",\"diarycontent\":\"맛있었다\",...}"} stipslashes를 수행안하면 다음과 같이 출력 \ <-는 String을 의미
                }else{
                    echo 'false';
                }

                break;
            }//switch-case diary end
            case 'shop':{
                
                break;
            }
            default:{

            }
        }
    }

/*
Function readDiary($uri)
1. purpose : Diary 
2. method : GET

*/


    function readDiary($uri){        
        switch($uri[2]){
            case 'diary':{
                echo $uri[3];
                echo 'readdiary';
                break;
            }

            case 'shop':{

                break;
            }
        }
    }

/*
Function writeDiary($uri)
1. purpose : WriteActivity에서 전송된 Text(json)와 image file(Array)를 저장
2. method :
- post 가 왔을 때 && diary 에 대한 요청이 왔을 때 실행
    --Text
    1) hashmap으로 전송된 text json에서 'diaryhashmap' key 를 사용하여 value를 $reqBody에 할당
    2) $reqBody는 json 형식으로 되있으므로 text 파일 json decoding 후 diaryJSON에 할당    
    3) diaryJSON의 각각 내용에 대하여 변수에 할당
    4) text에 해당하는 부분을 sql로 diary table에 저장

    --Image
    5) 이미피 파일 업로드 경로 지정
    6) 허용파일 설정
    7) $_FILES는 array(file,file,...) 다음과 같은 형식으로 오므로 foreach 사용
    8) 파일이름 설정
    9) 허용파일 확장자 Check
    10) move_uploaded_files를 통해 서버에 upload
    11) 이미지 경로 sql로 저장 / diary에 대한 key 저장
*/
    function writeDiary($uri){
        switch($uri[2]){
            case 'diary':{
                
                //DB Connect Setting with PDO
                $pdo = conn_db();
                                
                // $imagefilearray = $_FILES;
                // print_r($imagefilearray['0']);
                // echo count($_FILES);
                
                $user_id = 'macaron'; // 추후 user기능 추가 후 구현 예정
                $category = 'diary';
                //text 처리 - JSON
                $reqBody = $_POST['diaryhashmap'];                               
                $diaryJSON = json_decode($reqBody);
                $diary_title = $diaryJSON->{'diarytitle'};
                $diary_content = $diaryJSON->{'diarycontent'};
                $diary_macaronshop = $diaryJSON->{'diaryshopname'};
                $diary_date = $diaryJSON->{'diarydate'};
                // echo json_encode(array('diarytitle' => $diary_title, 'diarycontent' => $diary_content , 'diaryshopname'=> $diary_macaronshop, 'diarydate' => $diary_date),JSON_UNESCAPED_UNICODE);                                          
                
                //text sql 처리
                $stmt = $pdo->prepare('INSERT INTO diary (user_id,diary_title,diary_content,diary_shopname,diary_date) VALUES (:user_id,:diary_title,:diary_content,:diary_shopname,:diary_date)');
                $stmt->bindParam(':user_id',$user_id);
                $stmt->bindParam(':diary_title',$diary_title);
                $stmt->bindParam(':diary_content',$diary_content);
                $stmt->bindParam(':diary_shopname',$diary_macaronshop);
                $stmt->bindParam(':diary_date',$diary_date);
                if($stmt->execute())//SQL execute
                {
                    header("HTTP/1.1 201 Request Success");
                    $image_key = $pdo->lastInsertId();// 해당 다이어리의 pk - images table에 등록
                    /*
                    * mysql_insert_id pdo에서 작동안하는걸로 보임
                    - lastInsertID는 마지막으로 추가된 항목의 pk(auto increment이므로)를 반환함
                    - image_key에 사용 / 다이어리 추가에 대한 항목으로 클라이언트에 반환
                    */
                    echo json_encode(array('id' => $image_key, 'diary_title' => $diary_title),JSON_UNESCAPED_UNICODE);                    
                }else{
                    header("HTTP/1.1 409 Text Logic Conflict");
                    echo json_encode(array('id' => 'none','error'=> '409 Error'),JSON_UNESCAPED_UNICODE);
                }

                //이미지 처리
                $targetDir="./diaryimage/";
                $allowTypes = array('jpg','png','jpeg','gif');
                $statusMsg = $errorMsg = $errorUpload = $errorUploadType = '';                
                
                if(count($_FILES)!=0){
                    foreach($_FILES as $key=>$val){                     
                        
                        $fileName = basename($_FILES[$key]['name']);                    
                        $targetFilePath = $targetDir . $fileName;
                        
                        // Valid Check
                        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
                        if(in_array($fileType, $allowTypes)){ 
                            // Upload to server path
                            if(move_uploaded_file($_FILES[$key]['tmp_name'], $targetFilePath)){                                                             
                                //image file 관련 sql 처리                                
                                $stmt = $pdo->prepare('INSERT INTO macaronimages (user_id,category,image_key,image_path) VALUES (:user_id,:category,:image_key,:image_path)');
                                $stmt->bindParam(':user_id',$user_id);//일기 작성자를 기록
                                $stmt->bindParam(':category',$category);//이미지 카테고리 기록
                                $stmt->bindParam(':image_key',$image_key);//일기에 해당하는 primary key를 기록 - 추후 join에 사용
                                $stmt->bindParam(':image_path',$targetFilePath);//일기에 해당하는 primary key를 기록 - 추후 join에 사용
                                
                                if($stmt->execute())//후기를 DB에 입력
                                {
                                    header("HTTP/1.1 201 Request Success");
                                }else{
                                    header("HTTP/1.1 409 Image Logic Conflict");
                                }
                            }else{ 
                                $errorUpload .= $_FILES['tmp_name'][$key].' | ';                                
                            } 
                        }else{ 
                            $errorUploadType .= $_FILES['tmp_name'][$key].' | '; 
                        } 
                    }
                    // 이미지 저장 내역 반환 / category, image 갯수
                    echo json_encode(array('image_category' => $category,'image_count'=> count($_FILES)),JSON_UNESCAPED_UNICODE);
                }else{//이미지 없음
                    header("HTTP/1.1 201 Request Success");
                    echo json_encode(array('image_category' => $category,'image_count'=> count($_FILES)),JSON_UNESCAPED_UNICODE);
                }

                
                break;
            }//Write diary end

            case 'shop':{

                break;
            }//Write shop end
        }//switch end
    }//function end

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