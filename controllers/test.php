<?php
    // print_r(json_decode($_POST));
    // print_r($_POST);
    

require_once("db_controller.php");
// require_once("../helpers/utilities.php");
$main = new Main(new SqlStringBuilder(),new Model(),new MedRecvariables());
    if(isset($_POST["data"])){    
        $payload = json_decode($_POST["data"],true);
        // echo gettype($payload);
        // die();
        $payloadLen = count($payload);
        $payloadId = null;
        if(isset($payload[$payloadLen-1])){
            $payloadId = $payload[$payloadLen-1];
        }else{
            $payloadId = $payload['btn_id'];
        }
        
      
        switch($payloadId){
            case "show_date":
                // $und = $main -> getRow("sun_dates",array("unavailable"),array("*"),array("category"),null);
                // $nor = $main -> getRow("sun_dates",array("nomral"),array("*"),array("category"),null);
                $und = array();
                $nor = array();
                $spc = array();
                $daysBtn = array();

                // Specify the start date. This date can be any English textual format  
                // $date_from = "2020-02-03";   
                $dateF = gmdate("Y-m-d");
                $date_from = strtotime($dateF); // Convert date to a UNIX timestamp  
                
                // Specify the end date. This date can be any English textual format  
                $dateT = $main->getRow("settings",array("end_date"),array("value"),array("name"),null)[0]["value"];  
                $date_to = strtotime($dateT); // Convert date to a UNIX timestamp  
                
                // Loop from the start date to end date and output all dates inbetween  
                for ($i=$date_from; $i<=$date_to; $i+=86400) {  
                    $daysBtn[] = date("Y-m-d", $i);                    
                }    

                for ($i=0;$i<count($daysBtn);$i++){
                    $currdate = $daysBtn[$i];
                    $sundate = $main->getRow("sun_dates",array($currdate),array("*"),array("dates"),null);
                    if (count($sundate)>=1){
                        if($sundate[0]["category"]=="unavailable"){
                            $und[] = $currdate;
                        }else if($sundate[0]["category"]=="special"){
                            $specialDateId = $sundate[0]["id"];
                            $hourFrames =  $main->getRow("hour_frames",array($specialDateId),array("total_people"),array("sun_dates_id"),null);
                            $totalPeople = 0;
                            for($j=0;$j<count($hourFrames);$j++){
                                $totalPeople+=$hourFrames[$j]["total_people"];
                            }
                            $schler = $main->getRow("scheduler",array($currdate),array("date_due"),array("date_due"),null);
                            $numDateInScheduler = count($schler);
                            if($numDateInScheduler>=$totalPeople){
                                $und[] = $currdate;
                            }
                        }
                    }
                    else{
                            $normalDateId = 3;
                            $hourFrames = $main->getRow("hour_frames",array($normalDateId),array("total_people"),array("sun_dates_id"),null);
                            $totalPeople = 0;
                            for($j=0;$j<count($hourFrames);$j++){
                                $totalPeople+=$hourFrames[$j]["total_people"];
                            }
                            $schler = $main->getRow("scheduler",array($currdate),array("date_due"),array("date_due"),null);
                            $numDateInScheduler = count($schler);
                            if($numDateInScheduler>=$totalPeople){
                                $und[] = $currdate;
                            }
                        }
                        

                }
                // print_r($und);
                $res = array("una"=>$und,"dateF"=>$dateF,"dateT"=>$dateT);
                echo json_encode($res);
                

            //     $period = new DatePeriod(
            //         new DateTime(gmdate("Y-m-d")),
            //         new DateInterval('P1D'),
            //         new DateTime('2019-09-30')
            //    );
            //    foreach ($period as $key => $value) {
            //     $a =  $value->format('Y-m-d')." <br> ";                     
            //     }
            
                
                // print_r ($und);
            break;

            case "show_available":                
                $chosenDate = $payload[0];
                $res = array();
                $sundate = $main->getRow("sun_dates",array($chosenDate),array("id","category"),array("dates"),null);
                $sunLen = count($sundate);
                $hourFrame =null;
                if($sunLen==1){
                    $hourFrame= $main->getRow("hour_frames",array($sundate[0]["id"]),array("*"),array("sun_dates_id"),null);
                }else{
                    $hourFrame= $main->getRow("hour_frames",array(3),array("*"),array("sun_dates_id"),null);
                }
                $hourFrameLen = count($hourFrame);
                
                for($i=0;$i<$hourFrameLen;$i++){
                    $schler = $main->getRow("scheduler",array($chosenDate,$hourFrame[$i]["id"]),array("*"),array("date_due","hour_frame_id"),"AND");
                    $totalPeople = $hourFrame[$i]["total_people"];
                    $schlerLen = count($schler);
                    $res[]=array($totalPeople-$schlerLen,$hourFrame[$i]["hours"],$hourFrame[$i]["id"]);
                }
                echo json_encode($res);
            break;

            case "appoint_date":
               
                $chosenDate = $payload[0];
                $hourFrameId = $payload[1];
                // $hours =$payload[2];
                $due_date =  date('F d Y',strtotime($chosenDate));
            
                $regNum = "regNumber";
                $hourFrame= $main->getRow("hour_frames",array($hourFrameId),array("*"),array("id"),null);
                $schler = $main->getRow("scheduler",array($chosenDate,$hourFrameId),array("*"),array("date_due","hour_frame_id"),"AND");
                $totalPeople = $hourFrame[0]["total_people"];
                $schlerLen = count($schler);
                if($schlerLen < $totalPeople){
                    $res = $main->getRow("scheduler",array($regNum,0),array("*"),array("registration_no","is_done"),"AND");
                    if(count($res)==0){
                        $main->addNewRow("scheduler",array($regNum,$hourFrameId,$chosenDate,date('Y-m-d H:i:s'),0));
                        echo json_encode("You have successfully booked your appoitment.\nPlease honor this appointment.\nYour appointment date is ".$due_date." between the hours of ".$hourFrame[0]["hours"]);
                    }else{
                        $aptDate = $res[0]["date_due"];
                        $dd = date('F d Y',strtotime($aptDate));
                        echo json_encode("You have already booked an appointment\nYours appointment date is ".$dd);
                    }
                    
                }else{
                    echo json_encode("This slot has been taken.\n Please try a different slot or date");
                }
            break;
        }
    }
?>

