<?php
// header('Access-Control-Allow-Origin: *');
ini_set('display_errors',1);
// require_once("../../vendors/php-vendors/barcode/autoload.php");
require_once("../../controllers/db_controller.php");
require_once("../../helpers/utilities.php");
require_once("../../vendors/php-vendors/jwt/src/JWT.php");
require_once("../../helpers/jwt.php");

use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;
use \Firebase\JWT\JWT;

$main = new Main(new SqlStringBuilder(), new Model());
date_default_timezone_set("Africa/Accra");

//POST
if (isset($_POST["pData"])) {
    $payload = json_decode($_POST["pData"], true);
    $payloadLen = count($payload);
    $payloadId = null;
    if (isset($payload[$payloadLen - 1])) {
        $payloadId = $payload[$payloadLen - 1];
    } else {
        $payloadId = $payload['btn_id'];
    }
    switch ($payloadId) {
        case "add_sender":
            $data = $payload[1];
            // print_r($data);
            $items = [$data["sender_phone"], $data["sender_name"], $data["sender_country"]];
            $res = $main->getRow("sender", [$data["sender_phone"]], ["sender_name", "sender_phone", "sender_country"],["sender_phone"], null);
            
            if (count($res) > 0){
                $main->updateRow("sender", $items, ["sender_phone"], [$data["sender_phone"]], null, null);
                echo json_encode(["status"=>"Ok","msg"=>"Old"]);
            }else{
                $main->addNewRow("sender", $items);
                echo json_encode(["status"=>"Ok","msg"=>"New"]);
            }
            break;

        case "remove_sender":
            $data = $payload[1];

            try{
                $main->deleteRow("sender", ["id"], [$data["id"]], null);
                echo json_encode(["status"=>"Ok"]);
            }catch (Exception $e){
                echo json_encode(["msg"=>"something went wrong"]);
            }
            break;

        case "get_senders":
            $res = $main->getAllRows("sender", ["sender_phone", "sender_name", "sender_country"]);
            echo json_encode($res);
            break;

        case "get_sender":
            $data = $payload[1];

            $res = $main->getRow("sender", [$data["id"]], ["sender_phone", "sender_name", "sender_country"], ["id"], null);
            echo json_encode($res);
            break;

        case "add_transaction":
            $data = $payload[0]["data"];
            $token = $payload[0]["token"];
            $transaction_id = $payload[0]["transaction_id"];
            $exchange_rate_id = $payload[0]["exchange_rate_id"];
            $sender_telephone = $data[0]["value"];
            $sender_name = $data[1]["value"];
            $sender_country = $data[2]["value"];
            $admin_id = 1;

            $sender_id = null;
            $date_inserted = date('Y-m-d H:i:s');
            $items = [$sender_telephone, $sender_name, $sender_country,date('Y-m-d H:i:s')];
            $res = $main->getRow("sender", [$sender_telephone], ["sender_name", "sender_phone", "sender_country","id","date_inserted"],["sender_phone"], null);
            
            if (count($res) > 0){
                $main->updateRow("sender", $items, ["sender_phone"], [$sender_telephone], null, null);
                $sender_id = $res[0]["id"];
                $date_inserted = $res[0]['date_inserted'];
            }else{
                $main->addNewRow("sender", $items);
                $sender_id = $main->getLastId();
            }
            //FETCH TRANSCATION HERE AND GET THE BAL AND BAL_AFTER_TXN
            $bal = 0;
            $bal_after_txn = 0;
            $res = $main->getRow('transaction',[$transaction_id],['bal','bal_after_txn'],['transaction_id'],null);
            if(count($res)>=1){
                $bal = $res[0]['bal'];
                $bal_after_txn = $res[0]['bal_after_txn'];
            }
            $res5 = $main->getRow("exchange_rate",[$exchange_rate_id], ["s_rate", "r_rate"], ["id"], null);
            $s_rate = 0;
            $r_rate = 0;

            if (count($res5) >= 1){
                $s_rate = $res5[0]["s_rate"];
                $r_rate = $res5[0]["r_rate"];
            }
            $items = [
                $transaction_id,
                $data[5]["value"],
                $data[6]["value"],
                $data[7]["value"],
                $data[8]["value"],
                $data[3]["value"],
                $data[4]["value"],
                $exchange_rate_id,
                $data[10]["value"],
                $data[11]["value"],
                $data[12]["value"],
                $sender_id,
                $date_inserted,
                date('Y-m-d H:i:s'),
                $data[13]["value"],
                $admin_id,
                $bal,
                $bal_after_txn,
                $s_rate,
                $r_rate
            ];

            if ($transaction_id == null){
                $txnId = genId($main);
                $items[0] = $txnId;
                $main->addNewRow("transaction", $items);               
                echo json_encode(["status"=>"Ok","msg"=>""]);
            }else{
                $main->updateRow("transaction", $items, ["transaction_id"], [$transaction_id], null, null);
                echo json_encode(["status"=>"Ok"]);
            }
            break;

        case "get_transaction":
            
            break;

        case "get_exchange_rate":
            $data = $payload[1];
                $rates1 = $main->getRow("exchange_rate", [$data["s_country"], $data["r_country"]], ["s_rate", "r_rate", "id"], ["s_country", "r_country"], "and");
                $rates2 = $main->getRow("exchange_rate", [$data["r_country"], $data["s_country"]], ["r_rate", "s_rate", "id"], ["s_country", "r_country"], "and");

                $rates = $rates1;
                $flag = "normal";

                if (count($rates) <= 0){
                    $rates = $rates2;
                    $flag = "reversed";

                }
                
                if (count($rates) <=0){
                    echo json_encode(["msg"=>"No exchange rate found", "status"=>"Failed"]);
                    die();
                }
                $s_currency = $main->getRow("country", [$data["s_country"]], ["currency"], ["id"], null);
                $r_currency = $main->getRow("country", [$data["r_country"]], ["currency"], ["id"], null);

                $res = [
                    "s_rate"=>$rates[0]["s_rate"],
                    "r_rate"=>$rates[0]["r_rate"],
                    "id"=>$rates[0]["id"],
                    "s_currency"=>$s_currency[0]["currency"],
                    "r_currency"=>$r_currency[0]["currency"],
                ];
                
                echo json_encode(["data"=>$res, "status"=>"Ok","flag"=>$flag]);
            break;

        case "add_exchange_rate":
            $data = $payload[0]["data"];

            $s_country = $data[0]["value"];
            $r_country = $data[1]["value"];
            $s_rate = $data[2]["value"];
            $r_rate = $data[3]["value"];

            $items = [$s_country, $r_country, $s_rate, $r_rate];

            $main->addNewRow("exchange_rate", $items);
            
            echo json_encode(["status"=>"Ok"]);
            break;

        case "get_exchange_rates":
            $res = $main->getAllRows("exchange_rate", ["*"]);

            echo json_encode($res);
            break;

        case "update_exchange_rate":
            $data = $payload[0]["data"];
            $id = $payload[0]["id"];
            $s_country = $data[0]["value"];
            $r_country = $data[1]["value"];
            $s_rate = $data[2]["value"];
            $r_rate = $data[3]["value"];

            $items = [$s_country, $r_country, $s_rate, $r_rate];

            $main->updateRow("exchange_rate", $items,["id"], [$id], null, null);
            echo json_encode(["status"=>"Ok"]);
            break;

        case "delete_exchange_rate":
            try{
                $data = $payload[1];
            $id = $data["id"];

            $res =$main->deleteRow("exchange_rate", ["id"], [$id], null);

            if ($res){
                echo json_encode(["status"=>"Ok"]);
                die();
            }
            }catch(Exception $e){
                echo json_encode(["status"=>"Error","msg"=>"Unable to delete exchange rate"]);
            }
            break;

        case "add_country":
            $data = $payload[0]["data"];
            $country_name = $data[0]["value"];
            $country_code = $data[1]["value"];
            $country_currency = $data[2]["value"];

            $items = [$country_name, $country_code, $country_currency];
            
            $main->addNewRow("country", $items);

            echo json_encode(["status"=>"Ok"]);
            break;

        case "get_countries":
            $res = $main->getAllRows("country", ["*"]);

            echo json_encode($res);
            break;

        case "get_country":
            $data = $payload[1];
            $id = $data[0];
            $res = $main->getRow("country", [$id], ["name"], ["id"], null);

            if (count($res) == 0){
                echo json_encode(["status"=>"Error"]);
            }else{
                echo json_encode(["status"=>"Ok","data"=>$res]);
            }
            break;

        case "update_country":
            $data = $payload[0]["data"];
            $id = $payload[0]["id"];
            $country_name = $data[0]["value"];
            $country_code = $data[1]["value"];
            $country_currency = $data[2]["value"];

            $items = [$country_name, $country_code, $country_currency];

            $main->updateRow("country", $items,["id"],[$id], null, null);
            echo json_encode(["status"=>"Ok"]);
            break;

        case "delete_country":
            try{
                $data = $payload[1];
            $id = $data["id"];

            $res =$main->deleteRow("country", ["id"], [$id], null);

            if ($res){
                echo json_encode(["status"=>"Ok"]);
                die();
            }
            }catch(Exception $e){
                echo json_encode(["status"=>"Error","msg"=>"Unable to delete country"]);
            }
            
            break;

        case "deposit":
            $data = $payload[1];
            $admin = $data["admin"];
            $agent = $data["agent"];
            $amount = $data["amount"];
            $amount_balance = null;

            $res = $main->getRow("admin_balance", [$agent, 1],["*"],["agent_id", "is_current"], "and");

            if (count($res) == 0){
                $main->addNewRow("admin_balance", [$amount, $amount, $admin, date("Y-m-d H:i:s"),$agent, 1]);
                echo json_encode(["status"=>"Ok"]);
            }else{
                $amount_balance = $res[0]["amt_balance"]+$amount;
                $res =$main->updateRow("admin_balance", [0], ["agent_id", "is_current"], [$agent, 1], ["is_current"], "and");
                if($res){
                    $main->addNewRow("admin_balance", [$amount, $amount_balance, $admin, date("Y-m-d H:i:s"), $agent,1]);
                    echo json_encode(["status"=>"Ok"]);
                }else{
                    echo json_encode(["status"=>"Error"]);
                }
                
            }
            break;

        case "complete_transaction":
            $data = $payload[1];
            $txn_id = $data["txn_id"];
            $agent = null;
            $bal = null;
            $bal_after_txn = null;

            $txn = $main->getRow("transaction", [$txn_id],["*"],["transaction_id"],null);

            $agent = $txn[0]["admin_id"];
            $bal = $main->getRow("admin_balance", [$agent, 1],["*"],["agent_id", "is_current"], "and")[0]["amt_balance"];
            $bal_after_txn = $bal - ($txn[0]["s_amount"] + $txn[0]["total_commission"]);

            $main->updateRow("admin_balance", [$bal_after_txn], ["agent_id", "is_current"], [$agent, 1], ["amt_balance"], "and");
            $main->updateRow("transaction", ["complete", $bal, $bal_after_txn], ["transaction_id"], [$txn_id], ["status", "bal", "bal_after_txn"], null);

            echo json_encode(["status"=>"Ok"]);
            break;

        case "complete_transaction_bulk":
            foreach($payload[1] as $data){
                $txn_id = $data;
                $agent = null;
                $bal = null;
                $bal_after_txn = null;

                $txn = $main->getRow("transaction", [$txn_id],["*"],["transaction_id"],null);

                $agent = $txn[0]["admin_id"];
                $bal = $main->getRow("admin_balance", [$agent, 1],["*"],["agent_id", "is_current"], "and")[0]["amt_balance"];
                $bal_after_txn = $bal - ($txn[0]["s_amount"] + $txn[0]["total_commission"]);

                $main->updateRow("admin_balance", [$bal_after_txn], ["agent_id", "is_current"], [$agent, 1], ["amt_balance"], "and");
                $main->updateRow("transaction", ["complete", $bal, $bal_after_txn], ["transaction_id"], [$txn_id], ["status", "bal", "bal_after_txn"], null);
            }

            echo json_encode(["status"=>"Ok"]);
            break;
        

    } //end of main switch
} //end of post data if block

//GET
if (isset($_GET["gData"])) {
    $payload = json_decode($_GET["gData"]);
    $payloadLen = count($payload);
    $payloadId = $payload[$payloadLen - 1];

    switch ($payloadId) {
    } //end of switch statement
}//end of get data if block

?>