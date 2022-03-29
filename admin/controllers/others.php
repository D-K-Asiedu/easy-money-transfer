<?php 
require_once("../../controllers/db_controller.php");
$main = new Main(new SqlStringBuilder(),new Model());

function removePicDuplicates($main){
$res = $main->getAllRows('admission_list_fin',['photo']);
$path = '../../../old_site/site_new/admission/resources/img/passports/';
$newPath = '../resources/img/passport/';
// print_r($res);
foreach ($res as $r){
    $photo = $r['photo'];     
    copy($path.$photo,$newPath.$photo);
}
echo "done";
}


function swap($main){
    $res = $main->getRow("serial_pin",["GCB","2021/2022"],["id"],["bank","acad_year"],"AND");
    $res1 = $main->getRow("serial_pin",["PostOffice","2021/2022"],["id"],["bank","acad_year"],"AND");
    for($i=0;$i<count($res);$i++){
        $id = $res[$i]["id"];        
        $main->updateRow("serial_pin",["PostOffice"],["id"],[$id],["bank"],null);
    }
    for($i=0;$i<count($res1);$i++){
        $id2 = $res1[$i]["id"];
        $main->updateRow("serial_pin",["GCB"],["id"],[$id2],["bank"],null);
    }
}

// function updateApplicants($main){
//     if (($handle = fopen("unew.csv", "r")) !== FALSE) {
//         $counter = 0;
//       while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
//             $num = count($data);    
//             for ($c=0; $c < $num; $c++) {
//                 // echo $c." ".$data[$c] . "<br />\n";
//             }
//             $OT_short = $data[19];
//             $applicantId = $data[5];
//             $res = $main->updateRow('admission_list_fin',[$OT_short],['applicant_id'],[$applicantId],['OT_short'],null);
//             if($res){
//                 echo 'Ok '.$counter++."<br>";
//             }
//         }
//     fclose($handle);
//     echo 'done';
//     }
// }

function filterNewApplicants($main){
    $res1 = $main->getAllRows('admission_list_fin',["*"]);
    for($i=0;$i<count($res1);$i++){
        $data = $res1[$i];        
        $ucher_voucher_id = $data['ucher_voucher_id'];
        $date_of_birth = $data['date_of_birth'];
        $first_name = $data['first_name'];
        $middle_name = $data['middle_name'];
        $surname = $data['surname'];
        $applicant_id = $data['applicant_id'];
        $postal_address = $data['postal_address'];
        $photo = $data['photo'];
        $phoneNumber = $data['phoneNumber'];
        $long_name_prog = $data['long_name_prog'];
        $long_name_dept = $data['long_name_dept'];
        $Title = $data['Title'];
        $Status = $data['Status'];
        $Level = $data['Level'];
        $Offering_Type = $data['Offering_Type'];
        $Hall = $data['Hall'];
        $FEES = $data['FEES'];
        $MODE = $data['MODE'];
        $BANKS = $data['BANKS'];
        $OT_short = $data['OT_short'];
        $acdc = $data['accept_decline'];

        $res2 = $main->getRow('admission_list_fin_old_2',[$applicant_id],['*'],['applicant_id'],null);
        // print_r($res2);
        if(count($res2)==0){
            // echo 1;
            $res3 = $main->addNewRow('admission_list_filtered',[
                $ucher_voucher_id,
                $date_of_birth,
                $first_name,
                $middle_name,
                $surname,
                $applicant_id,
                $postal_address,
                $photo,
                $phoneNumber,
                $long_name_prog,
                $long_name_dept ,
                $Title ,
                $Status ,
                $Level ,
                $Offering_Type ,
                $Hall ,
                $FEES ,
                $MODE ,
                $BANKS ,
                $OT_short ,
                $acdc
                ]);
            if($res3){
                echo 'Ok inserted<br>';
            }
        }
    }
}
// filterNewApplicants($main);

function updateOrAddDetails($main){
    $res1 = $main->getAllRows('mampong_list',["*"]);    
    for($i=0;$i<count($res1);$i++){
        // print_r($res1[$i]);
        $data = $res1[$i];
        $ucher_voucher_id = $data['ucher_voucher_id'];
        $date_of_birth = $data['date_of_birth'];
        $first_name = $data['first_name'];
        $middle_name = $data['middle_name'];
        $surname = $data['surname'];
        $applicant_id = $data['applicant_id'];
        $postal_address = $data['postal_address'];
        $photo = $data['photo'];
        $phoneNumber = $data['phoneNumber'];
        $long_name_prog = $data['long_name_prog'];
        $long_name_dept = $data['long_name_dept'];
        $Title = $data['Title'];
        $Status = $data['Status'];
        $Level = $data['Level'];
        $Offering_Type = $data['Offering_Type'];
        $Hall = $data['Hall'];
        $FEES = $data['FEES'];
        $MODE = $data['MODE'];
        $BANKS = $data['BANKS'];
        $OT_short = $data['OT_short'];
        $res3 = $main->getRow('admission_list_fin',[$applicant_id],['*'],['applicant_id'],null);
        if(count($res3)>=1){
            $res2 = $main->updateRow('admission_list_fin',
            [$ucher_voucher_id,$date_of_birth,$first_name,$middle_name,$surname,$postal_address,$photo,$phoneNumber,$long_name_prog,$long_name_dept,$Title,$Status,$Level,$Offering_Type,$Hall,$FEES,$MODE,$BANKS,$OT_short],//new values
            ['applicant_id'],[$applicant_id],//criteria
            ['ucher_voucher_id','date_of_birth','first_name','middle_name','surname','postal_address','photo','phoneNumber','long_name_prog','long_name_dept','Title','Status','Level','Offering_Type','Hall','FEES','MODE','BANKS','OT_short'],//new values,//field names to receive new values
            null);
            if($res2){
                echo 'update Ok '.$i."<br>";
            }
        }else if (count($res3)==0){
            $res2 = $main->addNewRow('admission_list_fin',[$ucher_voucher_id,$date_of_birth,$first_name,$middle_name,$surname,$applicant_id,$postal_address,$photo,$phoneNumber,$long_name_prog,$long_name_dept,$Title,$Status,$Level,$Offering_Type,$Hall,$FEES,$MODE,$BANKS,$OT_short,0]);
            if($res2){
                echo 'insert Ok '.$i."<br>";
            }
        }else{
            echo "error<br>";
        }
    }
}

function updateApplicantsDetails($main,$filename){
    if (($handle = fopen($filename, "r")) !== FALSE) {
        $counter = 0;
      while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $num = count($data);    
            for ($c=0; $c < $num; $c++) {
                // echo $c." ".$data[$c] . "<br />\n";
            }
            $ucher_voucher_id = $data[0];
            $date_of_birth = $data[1];
            $first_name = $data[2];
            $middle_name = $data[3];
            $surname = $data[4];
            $applicant_id = $data[5];
            $postal_address = $data[6];
            $photo = $data[7];
            $phoneNumber = $data[8];
            $long_name_prog = $data[9];
            $long_name_dept = $data[10];
            $Title = $data[11];
            $Status = $data[12];
            $Level = $data[13];
            $Offering_Type = $data[14];
            $Hall = $data[15];
            $FEES = $data[16];
            $MODE = $data[17];
            $BANKS = $data[18];
            $OT_short = $data[19];
            $up_short = $data[20];

            // echo '<pre>'.print_r($data).'</pre>';
            $res1 = $main->getRow('admission_list_fin',[$applicant_id],['*'],['applicant_id'],null);
            if(count($res1)>=1){                
                $res2 = $main->updateRow('admission_list_fin',
                [$ucher_voucher_id,$date_of_birth,$first_name,$middle_name,$surname,$postal_address,$photo,$phoneNumber,$long_name_prog,$long_name_dept,$Title,$Status,$Level,$Offering_Type,$Hall,$FEES,$MODE,$BANKS,$OT_short,$up_short],//new values
                ['applicant_id'],[$applicant_id],//criteria
                ['ucher_voucher_id','date_of_birth','first_name','middle_name','surname','postal_address','photo','phoneNumber','long_name_prog','long_name_dept','Title','Status','Level','Offering_Type','Hall','FEES','MODE','BANKS','OT_short','up_short'],//new values,//field names to receive new values
                null);
                if($res2){
                    echo $applicant_id.' update Ok '.$counter++."<br>";
                }
            }else if (count($res1)==0){                
                $res2 = $main->addNewRow('admission_list_fin',[$ucher_voucher_id,$date_of_birth,$first_name,$middle_name,$surname,$applicant_id,$postal_address,$photo,$phoneNumber,$long_name_prog,$long_name_dept,$Title,$Status,$Level,$Offering_Type,$Hall,$FEES,$MODE,$BANKS,$OT_short,0,$up_short]);
                if($res2){
                    echo 'insert Ok '.$counter++."<br>";
                }
            }else{
                echo "error<br>";
            }   
        }
    fclose($handle);
    echo 'done';
    }
}

function delete($main,$filename){
    if (($handle = fopen($filename, "r")) !== FALSE) {        
      while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $num = count($data);    
            for ($c=0; $c < $num; $c++) {
                // echo $c." ".$data[$c] . "<br />\n";
            }
            $ucher_voucher_id = $data[0];
            // echo '<pre>'.print_r($data).'</pre>';
            $res1 = $main->deleteRow('admission_list_fin',["ucher_voucher_id"],[$ucher_voucher_id],null);
            if(count($res1)>=1){                
                echo "delete Ok<br>";
            }else{
                echo "error<br>";
            }   
        }
    fclose($handle);
    echo 'done';
    }
}

// updateApplicantsDetails($main,"new_23.csv");
// removePicDuplicates($main);


// delete($main,"new.csv");
// updateApplicants($main);
