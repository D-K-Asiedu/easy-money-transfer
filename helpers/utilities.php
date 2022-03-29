<?php 

function genId($main){
    $ym = $main->getROw("settings", ["year_month"],["value"],["prop"], null)[0]["value"];
    $txn_number = (int)$main->getROw("settings", ["id_number"],["value"],["prop"], null)[0]["value"];
    $id_prefix = $main->getROw("settings", ["id_prefix"],["value"],["prop"], null)[0]["value"];

    $currYm = date("ym");
    $currDate = date("ymd");

    $id = "";

    if ($currYm == $ym){
        $txn_number +=1;
    }else{
        $txn_number = 1;
        $ym = $currYm;
    }

    $id .=$id_prefix.$currDate.(sprintf('%04d', $txn_number));
    $main->updateRow("settings", [$ym],["prop"],["year_month"],["value"], null);
    $main->updateRow("settings", [$txn_number],["prop"],["id_number"],["value"], null);

    return $id;
}

function printBcode($inp,$type,$format){
    switch($type){
        case "html":
            $bar = new Picqer\Barcode\BarcodeGeneratorHTML();
            $code = $bar->getBarcode($inp,$bar::TYPE_CODE_128);
            return $code;
        break;

        case "png":
            $bar = new \Picqer\Barcode\BarcodeGeneratorPNG();                   
            $code = "data:image/png;base64,".base64_encode($bar->getBarcode($inp, $bar::TYPE_CODE_128));
            return $code;
        break;

        case "jpg":
        case "jpeg":
            $bar = new Picqer\Barcode\BarcodeGeneratorJPG();
            $code = "data:image/jpg;base64,".base64_encode($bar->getBarcode($inp, $bar::TYPE_CODE_128));
            return $code;
        break;

        case "svg":
            $bar = new Picqer\Barcode\BarcodeGeneratorSVG();
            $code = "data:image/svg+xml;base64,".base64_encode($bar->getBarcode($inp, $bar::TYPE_CODE_128));
            return $code;
        break;
    }
}

function getImagePath($content){
    if (trim($content)==""){
        return [];
    }
    $doc = new DOMDocument();
    @$doc->loadHTML($content);
    $imagepaths=array();
    $imageTags = $doc->getElementsByTagName('img');   
    foreach($imageTags as $tag) {
        $imagepaths[]=$tag->getAttribute('src');
    }
    return $imagepaths;
}

function bgColor(){
    return "rgba(".random_int(1,255).",".random_int(1,255).",".random_int(1,255).",0.8)";
}

function updateAppToken($main,$newAppToken){
    if($newAppToken){
        $main->updateRow('settings',[$newAppToken],['prop'],['CLIENT_ID'],['value'],null);
    }                        
}

function keepLogs($baseURL,$data){
    $ip = getUserIP();
    $filename = $baseURL.date('Y-m-d').'.txt'; 
    $file = fopen($filename, "a+"); 
    fwrite($file, json_encode($data)."=>>".date('h:i:s')."=>>".$ip.PHP_EOL);
    fclose($file);
}

function getUserIP() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

function apiRequest($data,$API_URL){    
    $curl = curl_init();
    $query = http_build_query($data);                        
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($curl,CURLOPT_URL,$API_URL);
    curl_setopt($curl,CURLOPT_POST,1);
    curl_setopt($curl,CURLOPT_POSTFIELDS,$query);  
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);          
    $res = curl_exec($curl);  
    if (curl_errno($curl)) {
        //something went wrong
        die('Couldn\'t send request: ' . curl_error($curl));
    }       
    curl_close($curl);                
    $data = json_decode($res);
    if($data == null){
        echo json_encode(["msg"=>$res,"status"=>"error"]);                             
        die();
    }                                           
    return get_object_vars($data);    
}

function groupArrayObjectByASelectedKey($arrayObject, $key) {
    $array = [];
    $visited = [];
    for ($i = 0; $i < count($arrayObject); $i++) {
        $subArray = [];
        $firstKey = $arrayObject[$i][$key];
        if (!in_array($firstKey,$visited)) {
            for ($j = 0; $j < count($arrayObject); $j++) {
                $newKey = $arrayObject[$j][$key];
                if ($firstKey == $newKey) {
                    $subArray[] =$arrayObject[$j];
                }
            }
            $visited[] = $firstKey;
        }
        if (count($subArray) != 0)
            $array[] = $subArray;
    }
    return $array;
}


function groupArrayObjectByASelectedKey1($arrayObject, $key) {
    $array = [];
    $visited = [];
    foreach($arrayObject as $obj){    
        $subArray = [];
        $firstKey = $obj;
        if (!in_array($firstKey,$visited)) {            
                foreach($arrayObject as $obj1){
                $newKey = $obj1;
                if ($firstKey == $newKey) {
                    $subArray[] =$newKey;
                }
            }
            $visited[] = $firstKey;
        }
        if (count($subArray) != 0)
            $array[] = $subArray;
    }
    return $array;
}

// sbTy3TfSPrLDWx5BBvXXW1A7sYewYAJqMDZPwfIlGuYC1
// '{
//     "recipient" : ["0245455876","0244972835"],
//     "sender" : "AAMUSTED",
//     "message": "API messaging is fun!",
//     "is_schedule" :"false",
//     "schedule_date": ""
// }'
function apiMsg($key,$body){
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.mnotify.com/api/sms/quick?key=$key",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>$body,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
}

function convertBase64ToFileSysName($base64,$baseURL){
    $acceptedFile= ['jpeg','jpg','png','pdf'];
    $imagePart = explode(";base64,",$base64);
    $extension = explode("/",$imagePart[0])[1];
    $imageBase64 = base64_decode(str_replace(" ","+",$imagePart[1]));
    $fileName = md5(date('Y-m-d H:i:s')).str_shuffle("ABCDEFG1234567890");
    $fullPath = null;
    if(in_array($extension,$acceptedFile)){
        $fullPath = $baseURL.$fileName.".".$extension;
        file_put_contents($fullPath,$imageBase64);
    }
    return $fullPath;
}

function getSingleRowDataGivenField($main,$table,$data,$field){      
    $d = [];
    for($i=0;$i<count($data);$i++){
        $sesId = $data[$i][$field];
        $res = $main->getRow($table,[$sesId],["*"],[$field],null);
        if(count($res)>=1){
            $d [] = $res[0];
        }
    }   
    return $d;
}

function getMultiRowsDataGivenField($main,$table,$data,$field){      
    $d = [];
    for($i=0;$i<count($data);$i++){
        $sesId = $data[$i][$field];
        $res = $main->getRow($table,[$sesId],["*"],[$field],null);
        if(count($res)>=1){
            for($j=0;$j<count($res);$j++){
                $d [] = $res[$j];
            }            
        }
    }   
    return $d;
}


function countParamsGiven2DArray($data,$field,$value){
    $counter = 0;
    for($i=0;$i<count($data);$i++){
        if(!isset($data[$i][$field])) continue;
        if($data[$i][$field] == $value){
            $counter +=1;
        }
    }    
    return $counter;
}

function countGroupsFromArrayObject($data,$group_name){    
    $res = [];
    for($j=0;$j<count($data);$j++){
        $group = $data[$j];       
        $counter = 0;         
        for($p=0;$p<count($group);$p++){
            $counter++;
        }
        $res [] = ['group_name'=>$group[0][$group_name],'group_count'=>$counter];       
    }
    return $res;
}



function getDataGivenForeignKey($main,$data,$pfkField,$cfkField,$childTbl){
    $d = [];
    for($i=0;$i<count($data);$i++){
        $fk = $data[$i][$pfkField];
        $res = $main->getRow($childTbl,[$fk],["*"],[$cfkField],null);
        for($j=0;$j<count($res);$j++){
            $d [] = $res[$j];
        }  
        // $d [] = $res;     
    }
    return $d;
}


function transformFieldTo2DArray($data,$keyfieldName,$propfieldName,$valueFieldName){    
    $d = [];  
    $arr = [];
    $cur = $data[0][$keyfieldName]; 

    for($i=0;$i<count($data);$i++){ 
        $new = $data[$i][$keyfieldName];          
        if($cur == $new){
            $propName = $data[$i][$propfieldName];
            $arr[$propName] = $data[$i][$valueFieldName];
            $allKeys = array_keys($data[$i]);              
            foreach($allKeys as $key){
                if($key !=$propfieldName && $key!=$valueFieldName && gettype($key)=='string'){
                    if(!isset($arr[$key]))
                        $arr[$key] = $data[$i][$key];//ADD THE OTHER KEYS APART FROM WHAT IS SPECIFIED               
                }
            }  
        }else{                         
            $cur = $new;
            $d[] = $arr;
            $arr = [];
            $i-=1;
        }         
    }
    //PUT THE LAST ONE IN
    $d []  = $arr; 
    return $d;
}


function convertBase64ToFileSysNameExtPassed($base64,$baseURL,$acceptedFile,$realExt){    
    $imagePart = explode(";base64,",$base64);
    $extension = explode("/",$imagePart[0])[1];
    $imageBase64 = base64_decode(str_replace(" ","+",$imagePart[1]));
    $fileName = md5(date('Y-m-d H:i:s')).str_shuffle("ABCDEFG1234567890");
    $fullPath = null;    
    if(in_array($extension,$acceptedFile)){        
        $fullPath = $baseURL.$fileName.".".$realExt;
        file_put_contents($fullPath,$imageBase64);
    }    
    return $fullPath;
}

function convertBase64ToFileUsrName($base64,$baseURL,$fn){
    $acceptedFile= ['jpeg','jpg','png','pdf'];
    $imagePart = explode(";base64,",$base64);
    $extension = explode("/",$imagePart[0])[1];
    $imageBase64 = base64_decode(str_replace(" ","+",$imagePart[1]));
    $fileName = explode(".",$fn)[0].'_'.md5(date('Y-m-d H:i:s')).str_shuffle("ABCDEFG1234567890");
    $fullPath = null;
    if(in_array($extension,$acceptedFile)){
        $fullPath = $baseURL.$fileName.".".$extension;
        file_put_contents($fullPath,$imageBase64);
    }
    return $fullPath;
}

function convertFileToBase64($path){    
    $imgData = file_get_contents($path);             
    $base64 = base64_encode($imgData);
    $data = 'data:'.mime_content_type($path).';base64,'.$base64;
    return $data;       
}

function getData($main,$table,$fdx,$critFdx,$es){    
    $res = $main->getAllLimitRows($table,$fdx,$_POST["start"],$_POST["length"]);
    $all = $main->getAllRows($table,null);
    $totaldata = count($all);
    $totalfiltered = $totaldata;
    
    if(!empty($_POST["search"]["value"])){                       
        $logOp = "OR";
        $ordBy = null;
        $ord = null;
        $lmStart = $_POST["start"];
        $lmLen = $_POST["length"]; 
        $value = $_POST["search"]["value"]; 
        if ($es == null){
            $res = $main->getSearch($table,$value,$fdx,$critFdx,$logOp,$ordBy,$ord,$lmStart,$lmLen);
        }else{
            $res = searchES($table,$value,$lmLen,$lmStart,$es);
        }   
                    
    }else{
        // $res = $main->getRow($table,array(1),array("*"),array(1),null);        
    }
    return array($res,$totalfiltered);        
}

function getCustomSelectData($main,$table,$fdx,$critFdx,$critVal,$logOp,$orderBy,$order,$es){            
    $res = $main->getCustomSelectNoAssignment($table,$fdx,$critFdx,$critVal,$logOp,$orderBy,$order,null,null);             
    $totaldata = count($res);
    $totalfiltered = $totaldata;
    
    if(!empty($_POST["search"]["value"])){                       
        $logOp = "AND";
        $ordBy = null;
        $ord = null;
        $lmStart = $_POST["start"];
        $lmLen = $_POST["length"]; 
        $value = $_POST["search"]["value"]; 
        if ($es == null){
            $res = $main->getCustomSelectNoAssignment($table,$fdx,$critFdx,$critVal,$logOp,$orderBy,$order,$lmStart,$lmLen);                 
        }else{
            $res = searchES($table,$value,$lmLen,$lmStart,$es);
        }   
                    
    }else{

    }
    return array($res,$totalfiltered);        
}


function loadData($totaldata,$data,$totalfiltered){                 
    $js = array(
        "draw"            => intval( $_POST['draw'] ),
        "recordsTotal"    => intval( $totaldata ),
        "recordsFiltered" => intval( $totalfiltered ),
        "data"            => $data
    );
    echo json_encode($js);
}

function getSuper($main,$table,$child_id,$parent_type){
    $res = $main->getRow($table,[$child_id],["*"],["id"],null);    
    if(count($res)>=1){        
        if($res[0]['type']!=$parent_type){
            $parent_id = $res[0]['super_ou_type'];                      
            return getSuper($main,$table,$parent_id,$parent_type);
        }else{
            return $res;
        } 
    }    
}

function isItemInOrigin($main,$childTable,$childCritVal,$fdx,$childCritFdx,$childSelectedFdx,$parentTable,$parentType,$originCrit,$comparedAgainst){
    $prg = $main->getRow($childTable,$childCritVal,$fdx,$childCritFdx,null);
    $isInOrigin = 0;
    for($i=0;$i<count($prg);$i++){
        $dept_id = $prg[$i][$childSelectedFdx];
        $cam = trim(getSuper($main,$parentTable,$dept_id,$parentType)[0][$originCrit]);
        if($cam == $comparedAgainst){
            $isInOrigin = 1;
            break;
        }        
    } 
    return $isInOrigin;
}

function login($main,$usr,$psd){    
    $res = $main->getRow('admin',[$usr],["id","name","password"],["username"],null);                                    
    if(count($res)==1){  
        $id = $res[0]["id"];
        $name = $res[0]["name"]; 
        $psdHash = $res[0]["password"];  
        if(password_verify($psd, $psdHash) || $psd == $psdHash){
            $token = Auth::generateToken($id); 
            $main->addNewRow('generated_tokens',[$token,$id,date('Y-m-d H:i:s')]);                                           
            echo json_encode(["token"=>$token,"name"=>$name,"status"=>"Ok"]); 
        } else{
            echo json_encode(["status"=>"error","msg"=>"Wrong password"]);
        }         
    }else{                
        echo json_encode(["status"=>"error","msg"=>"Wrong username"]);
    }
}

function hashPassword($psd){
    return $hash = password_hash($psd, PASSWORD_DEFAULT,array("cost"=>12));
}

function getPermissions($main,$adminId){                  
    $tables = array("admin_role","admin_perm","admin_role_link","admin_role_perm");
    $columns = array(
        array("custom_id","role"),                                   
        array("permission"),
        array("role_id","admin_id"),
        array("permission_id","ar_link_restricted")                 
    );
    $lequal = array($tables[0].".id",      $tables[1].".id",            $tables[0].".id");
    $requal = array($tables[2].".role_id", $tables[3].".permission_id", $tables[3].".role_id");
    $critFdx = array("admin_id","restricted","ar_link_restricted");
    $critVals = array($adminId,0,0);
    $orderCol = array("1");
    $logOp = "AND";                       
    $res = $main->getMultiJoinedRow($tables,$columns,$lequal,$requal,$critFdx,$critVals,$logOp,$orderCol,null,null);                                                              
    echo json_encode($res);    
}

function generateSerialPin($pid,$sn,$pin,$num,$emode,$bank,$finMode,$acadyr,$adminId,$printed,$expiryD){    
    // $l = array(["PID","Serial","Pin","Entry Mode","Date","IssuedBy","Expires","ElevateTo","Locked","Bank/Campus","FinMode","acad_year"]);
    $l = [];
    for($i=1;$i<=$num;$i++){         
        $p =  randomHybridPassword($pid); 
        $s =  randomNumPassword($sn);
        $pn =  randomHybridPassword($pin);           
        $l[] = [$p,$s,$pn,$emode,date("Y-m-d:H:i:s"),$adminId,$expiryD,$emode,0,$bank,$finMode,$acadyr,$printed,0];
    }
    return $l;
}

function randomHybridPassword($charlen) {
    $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < $charlen; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

function randomNumPassword($charlen) {
    $alphabet = '123456789';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < $charlen; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

function array2csv(array &$array)
{
   if (count($array) == 0) {
     return null;
   }
   ob_start();
   $df = fopen("php://output", 'w');
   fputcsv($df, array_keys(reset($array)));
   foreach ($array as $row) {
      fputcsv($df, $row);
   }
   fclose($df);
   return ob_get_clean();
}

function download_send_headers($filename) {
    // disable caching
    $now = gmdate("D, d M Y H:i:s");
    header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
    header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
    header("Last-Modified: {$now} GMT");

    // force download  
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");

    // disposition / encoding on response body
    header("Content-Disposition: attachment;filename={$filename}");
    header("Content-Transfer-Encoding: binary");
}

// function getDrugId($gs1,$main){
//     $res = $main->getRow("settings",array("drug_no","treatment"),array("value"),array("name","category"),"AND");
//     $num = count($res);
//     if($num<=0){
//         die("No drug ID found!");
//     }
//     $start = $res[0]["value"];       
//     $id = $gs1.(sprintf('%04d',$start));      
//     $main->updateRow("settings",array($start+1),array("name","category"),array("drug_no","treatment"),array("value"),"AND");  
//     return $id;
// }


// function getIdUsingUsername($table,$username,$main){      
//     $values=array($username);
//     $fields=array("username","admin_id");
//     $critFdx = array("username");    
//     $res = $main->getRow($table,$values,$fields,$critFdx,NULL); 
//     if(is_array($res) && $res[0]['username']==$username){
//         return $res[0]['admin_id'];
//     }else{
//         die('Please Login to complete this action');
//     }
// }//end of function 


// function array_push_assoc($array, $key, $value){
//     $array[$key] = $value;
//     return $array;
// }//end of function 




?>
