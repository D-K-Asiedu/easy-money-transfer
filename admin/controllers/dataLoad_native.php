
<?php
require_once("../../controllers/db_controller.php");
require_once("../../helpers/utilities.php");
require_once("../helpers/variables.php");
require_once("../../vendors/php-vendors/jwt/src/JWT.php");
require_once("../../helpers/jwt.php");
require_once("../../model/dt.php");
use \Firebase\JWT\JWT;

$main = new Main(new SqlStringBuilder(), new Model());
date_default_timezone_set("Africa/Accra");


ini_set('display_errors',1);
try{    
    switch($_POST["dataLoadId"]){
        case "get_events":  
            try{ 
                $token = $_POST['token'];
                $adminId = Auth::decodeToken($token);
                $ou = $main->getRow('admin_to_ou',[$adminId],['ou'],['admin_id'],null);
                $ouString = '(';
                $len = count($ou);
                for ($i=0;$i<$len;$i++){
                    $o = $ou[$i]['ou'];
                    $ouString .= "'$o'";
                    if($i != $len-1){
                        $ouString .=',';
                    }
                }
                $ouString .=')';                    
               
                $table = 'events'; 
                $primaryKey = 'id'; 
                // Array of database columns which should be read and sent back to DataTables.
                // The `db` parameter represents the column name in the database, while the `dt`
                // parameter represents the DataTables column identifier. In this case simple
                // indexes
                $columns = array(
                    array( 'db' => 'id', 'dt'=>0),                                               
                    array( 'db' => 'title', 'dt' => 1 ),
                    array( 'db' => 'date_inserted',  'dt' => 2 ),
                    array( 'db' => 'event_type',  'dt' => 3 ),
                    array( 'db' => 'added_by',  'dt' => 4 ),
                    array( 'db' => 'link', 'dt'=>5),                
                    array( 'db' => 'featured',  'dt' => 6 ),
                    array( 'db' => 'published_save',  'dt' => 7 ),
                    array( 'db' => 'show_hide',  'dt' => 8 )             
                ); 
                $res = SSP::complex ($_POST, null, $table, $primaryKey, $columns,"ou in $ouString",null);
                $data = $res['data'];
                for($i=0;$i<count($data);$i++){                
                    $id = $data[$i][0];
                    //REPLACE THESE INDEXES
                    $res['data'][$i][3] = $res['data'][$i][3] ==1 ? 'Announcement':'News';
                    $res['data'][$i][6] = $res['data'][$i][6] ? "<input type='checkbox' style='opacity:1;pointer-events:all' checked class='toggle_check' data-field='featured' data-id='$id'>": "<input type='checkbox' style='opacity:1;pointer-events:all' class='toggle_check' data-field='featured' data-id='$id'>";    
                    $res['data'][$i][7] = $res['data'][$i][7] ? "<input type='checkbox' style='opacity:1;pointer-events:all' checked class='toggle_check' data-field='published_save'  data-id='$id'>": "<input type='checkbox' style='opacity:1;pointer-events:all' class='toggle_check' data-field='published_save'  data-id='$id'>";    
                    $res['data'][$i][8] = $res['data'][$i][8] ? "<input type='checkbox' style='opacity:1;pointer-events:all' checked class='toggle_check' data-field='show_hide'  data-id='$id'>": "<input type='checkbox' style='opacity:1;pointer-events:all' class='toggle_check' data-field='show_hide'  data-id='$id'>";    
                    //ADD ON TO THESE INDEXES
                    $res['data'][$i][9]="<i class='fas fa-edit event_edit' data-id='$id'></i>";
                    $res['data'][$i][10]="<i class='fas fa-trash event_del' data-id='$id'></i>";
                    $res['data'][$i][11]="<i class='fas fa-eye event_view' data-id='$id'></i>";                        
                }                
                echo json_encode($res);
            }catch(Exception $e){
                echo $e->getMessage();
            }          
            break;    
            case "get_contents":  
                try{ 
                    $token = $_POST['token'];
                    $adminId = Auth::decodeToken($token);
                    $ou = $main->getRow('admin_to_ou',[$adminId],['ou'],['admin_id'],null);
                    $ouString = '(';
                    $len = count($ou);
                    for ($i=0;$i<$len;$i++){
                        $o = $ou[$i]['ou'];
                        $ouString .= "'$o'";
                        if($i != $len-1){
                            $ouString .=',';
                        }
                    }
                    $ouString .=')';                    
                    $table = 'contents_new'; 
                    $primaryKey = 'id'; 
                    // Array of database columns which should be read and sent back to DataTables.
                    // The `db` parameter represents the column name in the database, while the `dt`
                    // parameter represents the DataTables column identifier. In this case simple
                    // indexes
                    $columns = array(
                        array( 'db' => 'id', 'dt'=>0),                                               
                        array( 'db' => 'unique_name', 'dt' => 1 ),
                        array( 'db' => 'parent_id',  'dt' => 2 ),
                        array( 'db' => 'ou',  'dt' => 3 ),
                        array( 'db' => 'content_type',  'dt' => 4 ),
                        array( 'db' => 'name', 'dt'=>5),                
                        array( 'db' => 'show_hide',  'dt' => 6 ),
                    ); 
                    $res = SSP::complex ($_POST, null, $table, $primaryKey, $columns,"ou in $ouString",null);
                    $data = $res['data'];
                    for($i=0;$i<count($data);$i++){                
                        $id = $data[$i][0];
                        $uname = $data[$i][1];                       
                        $res['data'][$i][6] = $res['data'][$i][6] ? "<input type='checkbox' style='opacity:1;pointer-events:all' checked class='toggle_content_check' data-field='show_hide'  data-id='$id'>": "<input type='checkbox' style='opacity:1;pointer-events:all' class='toggle_content_check' data-field='show_hide'  data-id='$id'>";    
                        $res['data'][$i][7]="<i class='fas fa-edit content_edit' data-id='$id'></i>";
                        $res['data'][$i][8]="<i class='fas fa-trash content_del' data-id='$id'></i>";
                        $res['data'][$i][9]="<i class='fas fa-map content_extra' data-id='$uname'></i>";                        
                    }                
                    echo json_encode($res);
                }catch(Exception $e){
                    echo $e->getMessage();
                }          
                break;      
        }//switch

}catch(Exception $e){
    $err = $e->getMessage();      
}

?>