<?php

try
{
  // $file_handle = @fopen("../../vendors/php-vendors/jwt/src/JWT.php","r");//check if file is at specified path
  if (!file_exists('../../vendors/php-vendors/jwt/src/JWT.php')) {
    require_once("../vendors/php-vendors/jwt/src/JWT.php");//if file at the specified path is not found require the file with this new path
  }else{
    require_once("../../vendors/php-vendors/jwt/src/JWT.php");//else require the file with the specified path
  }  

  // $file_handle1 = @fopen("../../helpers/variables_all.php","r");//check if file is at specified path
  if (!file_exists('../../helpers/variables_all.php')) {
    require_once("../helpers/variables_all.php");//if file at the specified path is not found require the file with this new path
  }else{
    require_once("../../helpers/variables_all.php");//else require the file with the specified path
  }  
}catch (Exception $e){
    
}
  
use \Firebase\JWT\JWT;

class Auth{
    private static $tokenKey ='mytoken1234';  

    static function authOAdmission($token,$main){
      try{        
        $index_no = Auth::decodeToken($token);
        $res = $main->getRow('ho_adm',[$index_no],['index_no'],["index_no"],null);        
        if(count($res)>=1 && $index_no == $res[0]["index_no"]){
          
        }else{            
          header('Location:login');
          die("Sorry! You cannot access this page");
        }
      }catch(Exception $e){

      }
    }

    static function authAdmission($token,$main){
      try{             
        $applicant_id = Auth::decodeToken($token);
        $res = $main->getRow('admission_list_fin',[$applicant_id],['applicant_id'],["applicant_id"],null);
        if(count($res) >= 1 && $res[0]["applicant_id"]==$applicant_id){

        }else{
          $curl = curl_init();
          $data = array(
              'client_id'=>CLIENT_ID,
              'client_secret'=>CLIENT_SECRET,
              'pData' =>['endpoint'=>'verify_applicant','appId'=>$applicant_id]
          );
          $query = http_build_query($data);
          curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
          curl_setopt($curl,CURLOPT_URL,API_URL);
          curl_setopt($curl,CURLOPT_POST,1);
          curl_setopt($curl,CURLOPT_POSTFIELDS,$query); 
          curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);            
          $res = curl_exec($curl);            
          curl_close($curl);            
          $data = json_decode($res);                                     
          if($data->num >=1 && $data->res[0]->applicant_id == $applicant_id){
            
          }else{            
            header('Location:../admission/login');
            die("Sorry! You cannot access this page");
          }
        }
      }catch(Exception $e){
        // echo $e->getMessage();
        die("Page is protected");
      }
    }

    static function authAdmin($token,$main){
      try{        
        $id = Auth::decodeToken($token);
        $res = $main->getRow('admin',[$id],['username','name','id'],["id"],null);        
        if(count($res) == 1 && $res[0]["id"]==$id){              
          $tables = array("admin_role_files_link","admin_files","admin_role_link","admin_role");
          $columns = array(
              array("date_inserted"),                
              array("path","description"),
              array("role_id"),
              array("custom_id")                    
          );
          $lequal = array($tables[0].".file_id",$tables[0].".role_id",$tables[3].".id");
          $requal = array($tables[1].".id",$tables[3].".id",$tables[2].".role_id");
          $critFdx = array("admin_id","restricted","arf_link_restricted");
          $critVals = array($id,0,0);
          $orderCol = array("1");
          $logOp = "AND";                       
          $res1 = $main->getMultiJoinedRow($tables,$columns,$lequal,$requal,$critFdx,$critVals,$logOp,$orderCol,null,null);                                                                    
          return $res1;          
        }else{
          header("Location: login");
          die("Sorry! You cannot access this page");
        }
      }catch(Exception $e){
        echo $e->getMessage();
        header("Location: login");
        die("Page is protected");
      }
    }

    static function authWebEditor($token,$main){
      try{        
        $id = Auth::decodeToken($token);
        $res = $main->getRow('web_editors',[$id],['id'],["id"],null);
        if(count($res) == 1 && $res[0]["id"]==$id){

        }else{
          // header(500);
          die("Sorry! You cannot access this page");
        }
      }catch(Exception $e){
        die("Page is protected");
      }
    }

    static function decodeToken($token){
      $key = Auth::$tokenKey;
      $res = JWT::decode($token,$key,['HS256']);  
      return $res->username;    
    }//end of function 
  
  
  static function generateToken($username){
      $key = Auth::$tokenKey;
      $payload = [
          'iat' => time(),
          'iss' => 'localhost',
          'exp' => time()+(360*60),
          'username'=>$username
      ];
      $token = JWT::encode($payload,$key);
      return $token;
  }
  static function generateAppToken($secret){
    $key = Auth::$tokenKey;
    $payload = [
        'iat' => time(),
        'iss' => 'localhost',
        'exp' => time()+(365*60*60*24),
        'secret'=>$secret
    ];
    $token = JWT::encode($payload,$key);
    return $token;
  }

  static function decodeAppToken($token){
    $key = Auth::$tokenKey;
    $res = JWT::decode($token,$key,['HS256']);  
    return $res->secret;    
  }//end of function 
  //create a function the generate token by passing username and expiry as args
  
}
//  $token = Auth::generateAppToken('DN5@uTQZTdFG');
  // print_r($token);
       
?>

