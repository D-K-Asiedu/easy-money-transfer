<?php

Interface InterfaceSqlBuilder{

}

Class SqlStringBuilder implements InterfaceSqlBuilder{
    
    public function stripOff(){

    }

    public function getInsertBuilder($table,$fields,$place_holders){
        $fields_len = count($fields);
        $place_holders_len = count($place_holders);
        if($fields_len == $place_holders_len){
            $string = "INSERT INTO ";
            $string .=$table;
            $string .="(";
            for($i=0;$i<$fields_len;$i++){
                $string .=$fields[$i];
                if($i!=$fields_len-1)
                    $string .=",";
            }
            $string .=") ";
            $string .="VALUES";
            $string .="(";
            for($i=0;$i<$place_holders_len;$i++){
                $string .=$place_holders[$i];
                if($i!=$place_holders_len-1)
                    $string .=", ";
            }
            $string .=") ";
            return $string;
        }else{
            echo "Place holders length and fields length mismatch";
        }
    }//end of function


    public function getAllLimitRowsBuilder($table,$fields){
        $fields_len = count($fields);
        $string = "SELECT ";
        for($i=0;$i<$fields_len;$i++){
            $string .=$fields[$i];
            if($i!=$fields_len-1)
                $string .=", ";
        }
        $string .=" FROM ";
        $string .=$table." LIMIT :limit , :offset";       
        return $string;
    }//end of function 

    public function getCustomSelectBuilder($table,$fields,$criteria_fields,$criteria_values,$logical_operator){
        $criteria_fields_len = count($criteria_fields);
        $criteria_values_len = count($criteria_values);
        $fields_len = count($fields);
        $string =" SELECT ";
        for($i=0;$i<$fields_len;$i++){
            $string .=$fields[$i];
            if($i!=$fields_len-1)
                $string .=", ";
        }
        $string .=" FROM ";
        $string .=$table;
        $string .=" WHERE ";
        for($j=0;$j<$criteria_fields_len;$j++){
            if($criteria_fields_len > 1){
               if($criteria_fields_len==$criteria_values_len){
                   $string .=$criteria_fields[$j];
                   $string .=" = ";
                   $string .=$criteria_values[$j];
                   $string .=" ";            
                   if($j!=$criteria_fields_len-1)
                        $string .=$logical_operator;
                       $string .=" ";
               }else{
                   echo "criteria fields length and criteria place holders length mismatch";
               }                                                
           }else{
               $string .=$criteria_fields[$j];
               $string .=" = ";
               $string .=$criteria_values[$j];
           }
       }//end of second for       
       return $string;

    }

    public function getCustomUpdateNoAssignmentBuilder($table,$fields,$values,$criteria_fields,$criteria_values,$logical_operator){
        $fields_len = count($fields);
        $values_len = count($values); 
        $criteria_fields_len = count($criteria_fields);
        $criteria_values_len = count($criteria_values);

        if($fields_len != $values_len){
            echo "fields length and values length mismatch"; 
            die();
        }       
        $string =" UPDATE ".$table."  SET ";
        for($i=0;$i<$fields_len;$i++){
            $string .=$fields[$i]." = ".$values[$i];
            if($i!=$fields_len-1)
                $string .=", ";
        }        
        $string .=" WHERE ";
        for($j=0;$j<$criteria_fields_len;$j++){
            if($criteria_fields_len > 1){
               if($criteria_fields_len==$criteria_values_len){
                   $string .=$criteria_fields[$j];                
                   $string .=$criteria_values[$j];
                   $string .=" ";            
                   if($j!=$criteria_fields_len-1)
                        $string .=$logical_operator;
                       $string .=" ";
               }else{
                   echo "criteria fields length and criteria place holders length mismatch";
               }                                                
           }else{
               $string .=$criteria_fields[$j];               
               $string .=$criteria_values[$j];
           }
       }//end of second for       
       return $string;
    }

    public function getCustomUpdateBuilder($table,$fields,$values,$criteria_fields,$criteria_values,$logical_operator){
        $fields_len = count($fields);
        $values_len = count($values); 
        $criteria_fields_len = count($criteria_fields);
        $criteria_values_len = count($criteria_values);

        if($fields_len != $values_len){
            echo "fields length and values length mismatch"; 
            die();
        }   
        if($criteria_fields_len > 1 && $logical_operator==null){
            echo "logical operator cannot be null when criteria is more than 1";
            die();
        }    
        $string =" UPDATE ".$table."  SET ";
        for($i=0;$i<$fields_len;$i++){
            $string .=$fields[$i]." = ".$values[$i];
            if($i!=$fields_len-1)
                $string .=", ";
        }        
        $string .=" WHERE ";
        for($j=0;$j<$criteria_fields_len;$j++){
            if($criteria_fields_len > 1){
               if($criteria_fields_len==$criteria_values_len){
                   $string .=$criteria_fields[$j];
                   $string .=" = ";
                   $string .=$criteria_values[$j];
                   $string .=" ";            
                   if($j!=$criteria_fields_len-1)
                        $string .=$logical_operator;
                       $string .=" ";
               }else{
                   echo "criteria fields length and criteria place holders length mismatch";
               }                                                
           }else{
               $string .=$criteria_fields[$j];
               $string .=" = ";
               $string .=$criteria_values[$j];
           }
       }//end of second for              
       return $string;
    }

    public function getCustomSelectNoAssignmentBuilder($table,$fields,$criteria_fields,$criteria_values,$logical_operator,$orderBy,$order,$limStart,$limLen){
        $criteria_fields_len = count($criteria_fields);
        $criteria_values_len = count($criteria_values);
        $fields_len = count($fields);
        $string =" SELECT ";
        for($i=0;$i<$fields_len;$i++){
            $string .=$fields[$i];
            if($i!=$fields_len-1)
                $string .=", ";
        }
        $string .=" FROM ";
        $string .=$table;
        $string .=" WHERE ";
        for($j=0;$j<$criteria_fields_len;$j++){
            if($criteria_fields_len > 1){
               if($criteria_fields_len==$criteria_values_len){
                   $string .=$criteria_fields[$j];                
                   $string .=$criteria_values[$j];
                   $string .=" ";            
                   if($j!=$criteria_fields_len-1)
                        $string .=$logical_operator;
                       $string .=" ";
               }else{
                   echo "criteria fields length and criteria place holders length mismatch";
               }                                                
           }else{
               $string .=$criteria_fields[$j];            
               $string .=$criteria_values[$j];
           }
       }//end of second for 
       if($orderBy !=null && $order !=null){
        $string .=" ORDER BY ".$orderBy." ".$order;
        }

       if($limStart !=null && $limLen !=null){
           $string .=" LIMIT ".$limStart.",".$limLen;
       }              
       return $string;
    }

    public function getCustomSelectNoAssignmentLogOpBuilder($table,$fields,$criteria_fields,$criteria_values,$orderBy,$order,$limStart,$limLen){
        $criteria_fields_len = count($criteria_fields);
        $criteria_values_len = count($criteria_values);
        $fields_len = count($fields);
        $string =" SELECT ";
        for($i=0;$i<$fields_len;$i++){
            $string .=$fields[$i];
            if($i!=$fields_len-1)
                $string .=", ";
        }
        $string .=" FROM ";
        $string .=$table;
        $string .=" WHERE ";
        for($j=0;$j<$criteria_fields_len;$j++){
            if($criteria_fields_len > 1){
               if($criteria_fields_len==$criteria_values_len){
                   $string .=$criteria_fields[$j];                
                   $string .=$criteria_values[$j];
                   $string .=" ";                              
               }else{
                   echo "criteria fields length and criteria place holders length mismatch";
               }                                                
           }else{
               $string .=$criteria_fields[$j];            
               $string .=$criteria_values[$j];
           }
       }//end of second for 
       if($orderBy !=null && $order !=null){
        $string .=" ORDER BY ".$orderBy." ".$order;
        }

       if($limStart !=null && $limLen !=null){
           $string .=" LIMIT ".$limStart.",".$limLen;
       }         
       return $string;
    }
    

    public function getInsertIgnoreBuilder($table,$fields,$place_holders){
        $fields_len = count($fields);
        $place_holders_len = count($place_holders);
        if($fields_len == $place_holders_len){
            $string = "INSERT IGNORE INTO ";
            $string .=$table;
            $string .="(";
            for($i=0;$i<$fields_len;$i++){
                $string .=$fields[$i];
                if($i!=$fields_len-1)
                    $string .=",";
            }
            $string .=") ";
            $string .="VALUES";
            $string .="(";
            for($i=0;$i<$place_holders_len;$i++){
                $string .=$place_holders[$i];
                if($i!=$place_holders_len-1)
                    $string .=", ";
            }
            $string .=") ";
            return $string;
        }else{
            echo "Place holders length and fields length mismatch";
        }
    }//end of function


    public function getRowInOrderBuilder($table,$fields,$criteria_fields,$criteria_place_holders,$logical_operator,$whichCol,$order,$lim){
        $fields_len = count($fields);
        $criteria_fields_len = count($criteria_fields);
        $criteria_place_holders_len= count($criteria_place_holders);
        
        if($criteria_fields_len > 1 && $logical_operator==null){
            echo "logical operator cannot be null when criteria is more than 1";
            return;
        }
        
        $string = "SELECT ";
        for($i=0;$i<$fields_len;$i++){
            $string .=$fields[$i];
            if($i!=$fields_len-1)
                $string .=", ";
        }
        $string .=" FROM ";
        $string .=$table;
        $string .=" WHERE ";
        for($j=0;$j<$criteria_fields_len;$j++){
             if($criteria_fields_len > 1){
                if($criteria_fields_len==$criteria_place_holders_len){
                    $string .=$criteria_fields[$j];
                    $string .=" = ";
                    $string .=$criteria_place_holders[$j];
                    $string .=" ";            
                    if($j!=$criteria_fields_len-1)
                         $string .=$logical_operator;
                        $string .=" ";
                }else{
                    echo "criteria fields length and criteria place holders length mismatch";
                }                                                
            }else{
                $string .=$criteria_fields[$j];
                $string .=" = ";
                $string .=$criteria_place_holders[$j];
            }
        }//end of second for
        $string .=" ORDER BY ".$whichCol." ".$order;
        if ($lim!=null){
            $string .=" LIMIT ".$lim;
        }        
        return $string;
    }//end of function

    public function getAllRowsBuilder($table,$fields){
        $fields_len = count($fields);
        $string = "SELECT ";
        for($i=0;$i<$fields_len;$i++){
            $string .=$fields[$i];
            if($i!=$fields_len-1)
                $string .=", ";
        }
        $string .=" FROM ";
        $string .=$table;
        return $string;
    }//end of function 

    public function getAllDistinctRowsBuilder($table,$fields){
        $fields_len = count($fields);
        $string = "SELECT DISTINCT ";
        for($i=0;$i<$fields_len;$i++){
            $string .=$fields[$i];
            if($i!=$fields_len-1)
                $string .=", ";
        }
        $string .=" FROM ";
        $string .=$table;
        return $string;
    }//end of function 


    public function getRowsInRangeBuilder($table,$fields,$criteria_field,$begin_place_holder,$end_place_holder){
        $fields_len = count($fields);
        $string = "SELECT ";
        for($i=0;$i<$fields_len;$i++){
            $string .=$fields[$i];
            if($i!=$fields_len-1)
                $string .=", ";
        }
        $string .=" FROM ";
        $string .=$table;
        $string .= " WHERE ";
        $string .=$criteria_field;
        $string .=" BETWEEN ";
        $string .=$begin_place_holder;
        $string .=" AND ";
        $string .=$end_place_holder;
        return $string;
    }//end of function

    public function getRowBuilder($table,$fields,$criteria_fields,$criteria_place_holders,$logical_operator){
        $fields_len = count($fields);
        $criteria_fields_len = count($criteria_fields);
        $criteria_place_holders_len= count($criteria_place_holders);
        
        if($criteria_fields_len > 1 && $logical_operator==null){
            echo "logical operator cannot be null when criteria is more than 1";
            return;
        }
        
        $string = "SELECT ";
        for($i=0;$i<$fields_len;$i++){
            $string .=$fields[$i];
            if($i!=$fields_len-1)
                $string .=", ";
        }
        $string .=" FROM ";
        $string .=$table;
        $string .=" WHERE ";
        for($j=0;$j<$criteria_fields_len;$j++){
             if($criteria_fields_len > 1){
                if($criteria_fields_len==$criteria_place_holders_len){
                    $string .=$criteria_fields[$j];
                    $string .=" = ";
                    $string .=$criteria_place_holders[$j];
                    $string .=" ";            
                    if($j!=$criteria_fields_len-1)
                         $string .=$logical_operator;
                        $string .=" ";
                }else{
                    echo "criteria fields length and criteria place holders length mismatch";
                }                                                
            }else{
                $string .=$criteria_fields[$j];
                $string .=" = ";
                $string .=$criteria_place_holders[$j];
            }
        }//end of second for
        return $string;
    }//end of function

    public function getNullRowBuilder($table,$fields,$criteria_fields,$criteria_place_holders,$logical_operator){
        $fields_len = count($fields);
        $criteria_fields_len = count($criteria_fields);
        $criteria_place_holders_len= count($criteria_place_holders);
        
        if($criteria_fields_len > 1 && $logical_operator==null){
            echo "logical operator cannot be null when criteria is more than 1";
            return;
        }
        
        $string = "SELECT ";
        for($i=0;$i<$fields_len;$i++){
            $string .=$fields[$i];
            if($i!=$fields_len-1)
                $string .=", ";
        }
        $string .=" FROM ";
        $string .=$table;
        $string .=" WHERE ";
        for($j=0;$j<$criteria_fields_len;$j++){
             if($criteria_fields_len > 1){
                if($criteria_fields_len==$criteria_place_holders_len){
                    $string .=$criteria_fields[$j];
                    $string .=" <=> ";
                    $string .=$criteria_place_holders[$j];
                    $string .=" ";            
                    if($j!=$criteria_fields_len-1)
                         $string .=$logical_operator;
                        $string .=" ";
                }else{
                    echo "criteria fields length and criteria place holders length mismatch";
                }                                                
            }else{
                $string .=$criteria_fields[$j];
                $string .=" = ";
                $string .=$criteria_place_holders[$j];
            }
        }//end of second for
        return $string;
    }//end of function

    public function updateAllRowsBuilder($table,$fields,$place_holders){
        $fields_len = count($fields);
        $place_holders_len = count($place_holders);
        if($fields_len == $place_holders_len){
        $string = "UPDATE ";
        $string .=$table;
        $string .=" SET ";
        for($i=0;$i<$fields_len;$i++){
            $string .= $fields[$i]."=".$place_holders[$i];
            if($i!=$fields_len-1)
                $string .=", ";
        }
        return $string;
        }else{
            echo "Place holders length and fields length mismatch";
        }

    }//end of function

    public function updateRowsInRangeBuilder($table,$fields,$place_holders,$criteria_field,$begin_place_holder,$end_place_holder){
        $fields_len = count($fields);
        $place_holders_len = count($place_holders);
        if($fields_len == $place_holders_len){
            $string = "UPDATE ";
            $string .=$table;
            $string .=" SET ";
            for($i=0;$i<$fields_len;$i++){
                $string .= $fields[$i]."=".$place_holders[$i];
                if($i!=$fields_len-1)
                    $string .=", ";
            }
            $string .=" WHERE ";
            $string .=$criteria_field;
            $string .=" BETWEEN ";
            $string .=$begin_place_holder;
            $string .=" END ";
            $string .=$end_place_holder;
            return $string;
        }else{
            echo "Place holders length and fields length mismatch";
        }

    }//end of function

    public function updateRowBuilder($table,$fields,$place_holders,$criteria_fields,$criteria_place_holders,$logical_operator){
        $fields_len = count($fields);
        $place_holders_len = count($place_holders);
        $criteria_fields_len = count($criteria_fields);
        $criteria_place_holders_len= count($criteria_place_holders);

        if($criteria_fields_len > 1 && $logical_operator==null){
            echo "logical operator cannot be null when criteria is more than 1";
            return;
        }
        
        if($fields_len == $place_holders_len){
            $string = "UPDATE ";
            $string .=$table;
            $string .=" SET ";
            for($i=0;$i<$fields_len;$i++){            
                $string .= $fields[$i]." = ".$place_holders[$i];                
                if($i!=$fields_len-1)
                    $string .=", ";
            }//end of first for
            $string .=" WHERE ";
            for($j=0;$j<$criteria_fields_len;$j++){
                if($criteria_fields_len > 1){
                    if($criteria_fields_len==$criteria_place_holders_len){
                        $string .=$criteria_fields[$j];
                        $string .=" = ";
                        $string .=$criteria_place_holders[$j];
                        $string .=" ";            
                        if($j!=$criteria_fields_len-1)
                            $string .=$logical_operator;
                            $string .=" ";
                    }else{
                        echo "criteria fields length and criteria place holders length mismatch";
                    }                                                
                }else{
                    $string .=$criteria_fields[$j];
                    $string .=" = ";
                    $string .=$criteria_place_holders[$j];
                }
            
                }//end of second for             
            return $string;
        }else{
            echo "Place holders length and fields length mismatch";
        }
    }//end of function

    public function deleteAllRowsBuilder($table){
        $string = "DELETE ";    
        $string .="FROM ";
        $string .=$table;
        return $string;
    }//end of function

    public function deleteRowsInRangeBuilder($table,$criteria_field,$begin_place_holder,$end_place_holder){
        $string = "DELETE ";    
        $string .="FROM ";
        $string .=$table;
        $string .=" WHERE ";
        $string .=$criteria_field;
        $string .=" IN ";
        $string .="(";
        $string .=$begin_place_holder;
        $string .=",";
        $string .=$end_place_holder;
        $string .=")";

        return $string;
    }//end of function 

    public function deleteRowBuilder($table,$criteria_fields,$criteria_place_holders,$logical_operator){
       
        $criteria_fields_len = count($criteria_fields);
        $criteria_place_holders_len= count($criteria_place_holders);

        if($criteria_fields_len > 1 && $logical_operator==null){
            echo "logical operator cannot be null when criteria is more than 1";
            return;
        }
            
        $string = "DELETE ";
        $string .=" FROM ";
        $string .=$table;
        
        $string .=" WHERE ";
            for($j=0;$j<$criteria_fields_len;$j++){
                if($criteria_fields_len > 1){
                    if($criteria_fields_len==$criteria_place_holders_len){
                        $string .=$criteria_fields[$j];
                        $string .=" = ";
                        $string .=$criteria_place_holders[$j];
                        $string .=" ";            
                        if($j!=$criteria_fields_len-1)
                            $string .=$logical_operator;
                            $string .=" ";
                    }else{
                        echo "criteria fields length and criteria place holders length mismatch";
                    }                                                
                }else{
                    $string .=$criteria_fields[$j];
                    $string .=" = ";
                    $string .=$criteria_place_holders[$j];
                }
        
            }//end of second for             
        return $string;
       
    }//end of function

    public function getSearchBuilder($table,$fields,$criteria_fields,$criteria_place_holders,$logical_operator,$orderBy,$order,$limStart,$limLen){
        $fields_len = count($fields);
        $criteria_fields_len = count($criteria_fields);
        $criteria_place_holders_len= count($criteria_place_holders);
        
        if($criteria_fields_len > 1 && $logical_operator==null){
            echo "logical operator cannot be null when criteria is more than 1";
            return;
        }
        
        $string = "SELECT ";
        for($i=0;$i<$fields_len;$i++){
            $string .=$fields[$i];
            if($i!=$fields_len-1)
                $string .=", ";
        }
        $string .=" FROM ";
        $string .=$table;
        $string .=" WHERE (";
        for($j=0;$j<$criteria_fields_len;$j++){
             if($criteria_fields_len > 1){
                if($criteria_fields_len==$criteria_place_holders_len){
                    $string .=$criteria_fields[$j];
                    $string .=" LIKE ";
                    $string .=$criteria_place_holders[$j];
                    $string .=" ";            
                    if($j!=$criteria_fields_len-1)
                         $string .=$logical_operator;
                        $string .=" ";
                }else{
                    echo "criteria fields length and criteria place holders length mismatch";
                }                                                
            }else{
                $string .=$criteria_fields[$j];
                $string .=" LIKE ";
                $string .=$criteria_place_holders[$j];
            }
        }//end of second for
        $string .=")";

        if($orderBy!=null && $order!=null){
            $string .="ORDER BY ".$orderBy." ".$order;            
        }
        if ($limStart!=null || $limStart !=""){
            $string .=" LIMIT ".$limStart;
        } 
        if($limLen!=null || $limLen !=""){
            $string .= " ,".$limLen;
        }
        
        return $string;
    }//end of function

    function getJoinedTablesRowBuilder($tables,$columns,$lequal,$requal,$critFdx,$critPholder,$logOp,$orderCol,$lim,$offset){
        $numTables = count($tables);
        $numInnerColArray = count($columns);
        $critFdxLen = count($critFdx);
        $critPholderLen = count($critPholder);
        $lequalLen = count($lequal);
        $requalLen = count($requal);
        $numOrder = null;
        if($orderCol !=null){
            $numOrder = count($orderCol);
        }
        
        if( $numTables!= $numInnerColArray){
            echo "Number of tables and number of inner arrays of field names must be equal";
            return;
        }
        if($lequalLen!=$requalLen){
            echo "The number of array for left of the assignment and that of the right mismatches";
            return;
        }
        if($critPholderLen !=$critFdxLen){
            echo "Criteria fields length and criteria place holders length mismatch";
            return;
        }

        $string = "SELECT ";
        
        for($i=0;$i<$numTables;$i++){
            for($j=0;$j<count($columns[$i]);$j++){
                // echo $i."   ".$j."<br>";
                $string .= $tables[$i].".".$columns[$i][$j];
                if($i==$numTables-1 &&  $j==count($columns[$numTables-1])-1 ){
                    break;
                }else{
                    $string .=", ";
                }
                // $i!=$numTables-1 &&  $j!=count($columns[$numTables-1])-1 
            }//inner loop
        }//outer loop

        $string .=" FROM ";

        for($i=0;$i<$numTables;$i++){
            $string .=$tables[$i];
                if($i!=$numTables-1)
                    $string .=", ";
        }

        $string .=" WHERE ";
        
        for($i=0;$i<$lequalLen;$i++){
            $string .=$lequal[$i]." = ".$requal[$i];
            if($i!=$lequalLen-1)
                // $string .=" ".$logOp." ";
                $string .=" AND ";
        }
        $setTrue = true;
        for($j=0;$j<$critFdxLen;$j++){
            if($setTrue){
                // $string .=" ".$logOp." ";
                $string .=" AND (";
                $setTrue = false;
            }            
            if($critFdxLen > 1){                
                $string .=$critFdx[$j];
                $string .=" = ";
                $string .=$critPholder[$j];
                $string .=" ";            
                if($j!=$critFdxLen-1)
                        $string .=$logOp;
                    $string .=" ";                                                            
        }else{
            $string .=$critFdx[$j];
            $string .=" = ";
            $string .=$critPholder[$j];
        }
    }
    $string .=")";
    if($orderCol !=null){
        $string .= " ORDER BY ";
        for($i=0;$i<$numOrder;$i++){
            $string .=$orderCol[$i];
                if($i!=$numOrder-1)
                    $string .=", ";
        }
    }

    if ($lim!=null){
        $string .=" LIMIT :limit";
        if ($offset!=null){
            $string .= " , :offset";
        }
    }         
    return $string;
    }//end of function

    public function getJoinedRowBuilder($tables,$tbl1Fields,$tbl2Fields,$fieldToJoinOn,$criteria_table,$criteria_fields,$criteria_place_holders,$logical_operator,$orderBy,$order,$lim){        
        $criteria_fields_len = count($criteria_fields);
        $criteria_place_holders_len= count($criteria_place_holders);
        
        if(!is_array($tables)){
            echo "First param must be an array";
            return;
        }
        if(!is_array($tbl1Fields)){
            echo "Second param must be an array";
            return;
        }
        if(!is_array($tbl2Fields)){
            echo "Third param must be an array";
            return;
        }
        if (!is_array($fieldToJoinOn) &&count($fieldToJoinOn)==2){
            echo "Fourth param must be an array and length must to 2";
            return;
        }
        $tbl1Len = count($tbl1Fields);
        $tbl2Len = count($tbl2Fields);
        $tblLen = count($tables);
        $string ="SELECT ";
        for($i=0;$i<$tbl1Len;$i++){
            $string .= $tables[0].".".$tbl1Fields[$i];
            if($i!=$tbl1Len-1)
                $string .=", ";
        }
        $string .=",";
        for($i=0;$i<$tbl2Len;$i++){
            $string .= $tables[1].".".$tbl2Fields[$i];
            if($i!=$tbl2Len-1)
                $string .=", ";
        }
        $string .=" FROM ";
        for($i=0;$i<$tblLen;$i++){
            $string .=$tables[$i];
            if($i!=$tblLen-1)
                $string .=", ";
        }
        $string .= " WHERE ";           
        $string .=$tables[0].".".$fieldToJoinOn[0] ."=". $tables[1].".".$fieldToJoinOn[1]." ".$logical_operator." ";
        for($j=0;$j<$criteria_fields_len;$j++){
            if($criteria_fields_len > 1){
                if($criteria_fields_len==$criteria_place_holders_len){
                    $string .=$criteria_table.".".$criteria_fields[$j];
                    $string .=" = ";
                    $string .=$criteria_place_holders[$j];
                    $string .=" ";            
                    if($j!=$criteria_fields_len-1)
                        $string .=$logical_operator;
                        $string .=" ";
                }else{
                    echo "criteria fields length and criteria place holders length mismatch";
                }                                                
            }else{
                $string .=$criteria_table.".".$criteria_fields[$j];
                $string .=" = ";
                $string .=$criteria_place_holders[$j];
            }//end of else
        }//end of for
        if($orderBy!=null && $order!=null){
            $string .="ORDER BY ".$orderBy." ".$order;
            if ($lim!=null){
                $string .=" LIMIT ".$lim;
            } 
        } 
       
        return $string; 
    }//end of function
    

    public function customAddNewRowBuilder($table,$fields,$values){
        $fields_len = count($fields);        
        $values_len = count($values);
        if($values_len !=$fields_len){
            echo "Fields mismatches values";
            return;
        }
        $string = "INSERT INTO ";
        $string .=$table;
        $string .="(";
        for($i=0;$i<$fields_len;$i++){
            $string .=$fields[$i];
            if($i!=$fields_len-1)
                $string .=",";
        }
        $string .=") ";
        $string .="VALUES";
        $string .="(";
        for($i=0;$i<$values_len;$i++){
            
            $string .=$values[$i];
            if($i!=$fields_len-1)
                $string .=",";
        }           
    
        $string .=") ";           
        return $string;
    }//end of function 

}//end of class
?>
   