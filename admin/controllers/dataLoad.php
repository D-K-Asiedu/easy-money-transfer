
<?php

use \Firebase\JWT\JWT;

try {
    require_once('../../controllers/db_controller.php');
    require_once("../../helpers/utilities.php");
    require_once("../../vendors/php-vendors/jwt/src/JWT.php");
    require_once("../../helpers/jwt.php");

    $main = new Main(new SqlStringBuilder(), new Model());
} catch (Exception $e) {
    $err = $e->getMessage();
}

switch ($_POST["dataLoadId"]) {
    case "get_sales":
        $token = $_POST["adminId"];
        $adminId = Auth::decodeToken($token);
        $acadYr = $main->getRow("settings",["acad_year"],["value"],["prop"],null)[0]["value"];            
        $tables = array("entry_mode","serial_pin");
        $columns = array(
            array("name"),                                   
            array("serial_no","pin","has_printed","id","date_inserted")                    
        );
        $lequal = array($tables[0].".id");
        $requal = array($tables[1].".entry_mode");
        $critFdx = array("issued_by","acad_year");
        $critVals = array($adminId,$acadYr);
        $orderCol = array("1");
        $logOp = "AND";                       
        $res = $main->getMultiJoinedRow($tables,$columns,$lequal,$requal,$critFdx,$critVals,$logOp,$orderCol,null,null);                                                          
        $totaldata = count($res);
        $allData = count($main->getAllRows("serial_pin",null));
        for ($i = 0; $i < $totaldata; $i++) {
            $subarray = array();
            $subarray[] = $res[$i]["serial_no"];
            $subarray[] = $res[$i]["pin"];
            $subarray[] = $res[$i]["name"];
            $subarray[] = $res[$i]["has_printed"]==1?"Yes":"No";
            $subarray[] = $res[$i]["date_inserted"];
            $id = $res[$i]["id"];
            $subarray[] = '<i style="cursor:pointer" class="fas fa-print text-primary print" data-id="'.$id.'"></i>';            
            $data[] = $subarray;
        }
        loadData($allData, $data, $totaldata);
        break;
    case "diagData":
        $table = "diagnosis";
        $fields = array("*");
        $critFdx = array("code", "long_description");
        $data = array();
        $res = getData($main, $table, $fields, $critFdx, $es);
        $totaldata = count($res[0]);
        for ($i = 0; $i < $totaldata; $i++) {
            $subarray = array();
            $subarray[] = $res[0][$i]["code"];
            $subarray[] = $res[0][$i]["long_description"];
            $subarray[] = '<input type="checkbox" class="chk" id="' . $res[0][$i]['code'] . '">';
            $data[] = $subarray;
        }
        loadData($totaldata, $data, $res[1]);
        break;
    case "pharmTrtData":
        $table = "treatment";
        $fields = array("*");
        $critFdx = array("drug_no", "generic_name", "category", "brand_names");
        $data = array();
        $res = getData($main, $table, $fields, $critFdx, $es);
        $totaldata = count($res[0]);
        for ($i = 0; $i < $totaldata; $i++) {
            $subarray = array();
            $drugNum = $res[0][$i]["drug_no"];
            $subarray[] = $drugNum;
            $subarray[] = $res[0][$i]["generic_name"];
            $subarray[] = $res[0][$i]["unit_of_pricing"];
            $subarray[] = $res[0][$i]["price"];
            $subarray[] = $res[0][$i]["level_of_prescribing"];
            $subarray[] = $res[0][$i]["category"];
            $subarray[] = $res[0][$i]["qty"];
            $subarray[] = $res[0][$i]["cubicle"];
            // $subarray[] = $res[0][$i]["is_nhis"];
            $storeItems = $main->getRowInOrder("store_received", array($drugNum), array("remaining", "item_id_rec", "date_received"), array("item_id_rec"), null, ["3"], ['DESC'], null);
            if (count($storeItems) >= 1) {
                $currentStoreQty = $storeItems[0]["remaining"];
                if ($currentStoreQty == 0) {
                    $subarray[] = '<i class="fas fa-dot-circle text-danger"></i>';
                } else {
                    $subarray[] = '<i class="fas fa-dot-circle text-success"></i>';
                }
            } else {
                $subarray[] = '<i class="fas fa-dot-circle text-danger"></i>';
            }
            $subarray[] = '<a class="drugEdit" data-toggle="modal" data-target="#drugEditModal" id="edt_' . $res[0][$i]["drug_no"] . '"> <i style="color:green" class="fas fa-edit"></i> </a>';
            $subarray[] = '<a class="drugDel" id="del_' . $res[0][$i]["drug_no"] . '"> <i style="color:red" class="fas fa-trash"></i> </a>';
            $subarray[] = '<a class="drugPrt" id="prt_' . $res[0][$i]["drug_no"] . '"> <i style="color:blue" class="fas fa-print"></i> </a>';
            $data[] = $subarray;
        }
        loadData($res, $data, $res[1]);
        break;
}

?>