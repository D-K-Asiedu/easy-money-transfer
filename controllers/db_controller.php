<?php
require_once(dirname(__FILE__,2)."/helpers/string_builders.php");
require_once(dirname(__FILE__,2)."/model/model.php");
// require_once(dirname(__FILE__,2)."/config/dbvariables.php");

class Main{
    
    private $tableNames = null; 
    private $builder = null;
    private $model = null;
    private $dbVars = null;
    private $fields = null;
    //private $placeHolders = null;
    private $tableNamesLen = 0;

    public function __construct(InterfaceSqlBuilder $builder,InterfaceModel $model){
        // $this->dbVars   = $dbVars;
        $this->builder  = $builder;
        $this->model    = $model;
        // $this->tableNames = $dbVars->getTableNames();
        $this->tableNames = $this->getTableNames();
        $this->tableNamesLen = count($this->tableNames); 
        $this->fields = array();
        //$this->placeHolders = array();
       
        foreach($this->tableNames as $table){
        //    $this->fields[] = $dbVars->getTableFields($table);
            $fdx = $this->model->getTableFields($table);            
            $this->fields[] = array_slice($fdx,1);           
        }//end of foreach         
    }//end of Main function

    private function getPlaceHolders($fields){        
        $placeHolders = array();        
        foreach($fields as $field){
            $placHolder =':'.$field;
            $placeHolders[]=$placHolder;            
        }
        return $placeHolders;
    }

    private function genCritPholders($critFdx){
        $critPholder =array();
        foreach($critFdx as $fd){
            $ph = ":".$fd."_";
            $critPholder[] = $ph;
        }       
        return $critPholder;
    }

    public function getAllDistinctRows($table,$fdx){              
        for($i=0;$i<$this->tableNamesLen;$i++){
            if($table == $this->tableNames[$i]){                
                if($fdx != null){
                    $fields = $fdx;
                } else{
                    $fields = $this->fields[$i];
                }   
                $sql = $this->builder->getAllDistinctRowsBuilder($this->tableNames[$i],$fields);                
                $res = $this->model->getAllRows($sql);                   
            }//end of if            
        }//end of for
        return $res;
    }//end of function

    private function getTableNames(){
        return $this->model->getTableNames();   
    }

    public function addNewRow($table,$values){
        for($i=0;$i<$this->tableNamesLen;$i++){
            if($table == $this->tableNames[$i]){
                $pholders =  $this->getPlaceHolders($this->fields[$i]);
            // $pholders =  $this->dbVars->getPlaceHolders($this->fields[$i]);
            $sql = $this->builder-> getInsertBuilder($this->tableNames[$i],$this->fields[$i],$pholders);            
                return $this->model->insert($sql,$pholders,$values);   
            }//end of if                       
        }//end of for        
    }//end of addNewUser

    public function addNewRowIgnore($table,$values){
        for($i=0;$i<$this->tableNamesLen;$i++){
            if($table == $this->tableNames[$i]){
                $pholders =  $this->getPlaceHolders($this->fields[$i]);
            // $pholders =  $this->dbVars->getPlaceHolders($this->fields[$i]);
             $sql = $this->builder-> getInsertIgnoreBuilder($this->tableNames[$i],$this->fields[$i],$pholders);            
                return $this->model->insert($sql,$pholders,$values);   
            }//end of if                       
        }//end of for        
    }//end of addNewUser
    
    public function getLastId(){
        return $this->model->getLastId();
    }
      

    public function updateRow($table,$values,$critFdx,$critVals,$custom_cols,$logOp){         
        $critPholder =$this->genCritPholders($critFdx);
        $newFields = array();
        $newPlaceHolders = array();
        for($i=0;$i<$this->tableNamesLen;$i++){
        if($this->tableNames[$i] == $table){
            if($custom_cols!=null){
                foreach($custom_cols as $a){                    
                    $newFields[] = $a;
                }
                $newPlaceHolders = $this->getPlaceHolders($newFields);
                // $newPlaceHolders = $this->dbVars->getPlaceHolders($newFields);
                $critFdxLen = count($critFdx);                                    
            }else{                        
                $newFields = $this->fields[$i];
                $newPlaceHolders = $this->getPlaceHolders($this->fields[$i]);                
                // $newPlaceHolders = $this->dbVars->getPlaceHolders($this->fields[$i]);                
            }//end of else 
            $critFdxLen = count($critFdx); 
            for($j=0;$j<$critFdxLen;$j++){                
                if($key = array_search(str_replace("_","",$critPholder[$j]), $newPlaceHolders)){                    
                    unset($newPlaceHolders[$key]);
                    $newPlaceHolders =array_values($newPlaceHolders);
                }                        
                if($key = array_search($critFdx[$j], $newFields)){                    
                    unset($newFields[$key]);
                    $newFields =array_values($newFields);
                }                                                
            }//end of for loop
            // print_r($newFields);
            // print_r($newPlaceHolders);                                                  
            $sql = $this->builder->updateRowBuilder($this->tableNames[$i],$newFields,$newPlaceHolders,$critFdx,$critPholder,$logOp);           
            return $this->model->updateRow($sql,$newPlaceHolders,$values,$critPholder,$critVals);                                     
            }//end of if
        }//end of for loop            
    }//end of updateUser

    public function updateAllRows($table,$values){
        for($i=0;$i<$this->tableNamesLen;$i++){
            if($this->tableNames[$i] == $table){ 
            // $pholders =  $this->dbVars->getPlaceHolders($this->fields[$i]);  
            $pholders =  $this->getPlaceHolders($this->fields[$i]);      
            $sql = $this->builder->updateAllRowsBuilder($this->tableNames[$i],$this->fields[$i],$pholders);           
             $this->model->updateAllRows($sql,$pholders,$values);
             }//end of if
         }//end of for loop
    }

    public function deleteRow($table,$critFdx,$critVals,$logOp){
        $critPholder = $this->genCritPholders($critFdx);        
        for($i=0;$i<$this->tableNamesLen;$i++){
            if($this->tableNames[$i] == $table){           
             $sql = $this->builder->deleteRowBuilder($this->tableNames[$i],$critFdx,$critPholder,$logOp);           
             return $this->model->deleteRow($sql,$critPholder,$critVals);
             }//end of if
        }//end of for loop
    }//end of function

    public function getRow($table,$values,$fields,$critFdx,$logOp){
        $critPholder = $this->genCritPholders($critFdx);          
        for($i=0;$i<$this->tableNamesLen;$i++){              
            if($this->tableNames[$i] == $table){                
                $sql = $this->builder->getRowBuilder($table,$fields,$critFdx,$critPholder,$logOp);                                  
                $res= $this->model->getRow($sql,$critPholder,$values);                                        
            }//end of if
        }//end of for
        return $res;
    }//end of function

    function getMultiJoinedRow($tables,$columns,$lequal,$requal,$critFdx,$critVals,$logOp,$orderCol,$limit,$offset){
        for($i=0;$i<$this->tableNamesLen;$i++){
            if($this->tableNames[$i] == $tables[0] /*|| $this->tableNames[$i] == $tables[1]*/){
            $critPholder = $this->genCritPholders($critFdx);            
            $sql = $this->builder->getJoinedTablesRowBuilder($tables,$columns,$lequal,$requal,$critFdx,$critPholder,$logOp,$orderCol,$limit,$offset); 
            $l = null;
            $o = null;
            if($limit !=null){
                $l = array(":limit",$limit);
                if($offset !=null){
                    $o = array(":offset",$offset);
                }
            }                      
            $res =$this->model->getMultiJoinedRow($sql,$critPholder,$critVals,$l,$o);            
            }
        }//end of for loop
        return $res;    
    }//end of function 

    public function getCustomSelect($table,$fdx,$critFdx,$critVals,$logOp){
        for($i=0;$i<$this->tableNamesLen;$i++){
            if($this->tableNames[$i] == $table){                
                $sql = $this->builder->getCustomSelectBuilder($table,$fdx,$critFdx,$critVals,$logOp);
                return $res = $this->model->getCustomSelectSqlExec($sql);
            }
        }
    }

    public function getCustomSelectNoAssignment($table,$fdx,$critFdx,$critVals,$logOp,$orderBy,$order,$lmStart,$lmLen){
        for($i=0;$i<$this->tableNamesLen;$i++){
            if($this->tableNames[$i] == $table){                
                $sql = $this->builder->getCustomSelectNoAssignmentBuilder($table,$fdx,$critFdx,$critVals,$logOp,$orderBy,$order,$lmStart,$lmLen);
                return $res = $this->model->getCustomSelectSqlExec($sql);
            }
        }
    }

    public function getCustomSelectNoAssignmentLogOp($table,$fdx,$critFdx,$critVals,$orderBy,$order,$lmStart,$lmLen){
        for($i=0;$i<$this->tableNamesLen;$i++){
            if($this->tableNames[$i] == $table){                
                $sql = $this->builder->getCustomSelectNoAssignmentLogOpBuilder($table,$fdx,$critFdx,$critVals,$orderBy,$order,$lmStart,$lmLen);
                return $res = $this->model->getCustomSelectSqlExec($sql);
            }
        }
    }

    public function getCustomUpdateNoAssignment($table,$fdx,$values,$critFdx,$critVals,$logOp){
        for($i=0;$i<$this->tableNamesLen;$i++){
            if($this->tableNames[$i] == $table){                
                $sql = $this->builder->getCustomUpdateNoAssignmentBuilder($table,$fdx,$values,$critFdx,$critVals,$logOp);
                return $res = $this->model->getCustomSqlExec($sql);
            }
        }
    }

    public function getCustomUpdate($table,$fdx,$values,$critFdx,$critVals,$logOp){
        for($i=0;$i<$this->tableNamesLen;$i++){
            if($this->tableNames[$i] == $table){                
                $sql = $this->builder->getCustomUpdateBuilder($table,$fdx,$values,$critFdx,$critVals,$logOp);
                return $res = $this->model->getCustomSqlExec($sql);
            }
        }
    }

    public function getOpenQuery($sql){
        return $res = $this->model->getCustomSelectSqlExec($sql);
    }

    public function getNullRow($table,$values,$fields,$critFdx,$logOp){
        $critPholder = $this->genCritPholders($critFdx);
        for($i=0;$i<$this->tableNamesLen;$i++){
            if($this->tableNames[$i] == $table){
                $sql = $this->builder->getNullRowBuilder($table,$fields,$critFdx,$critPholder,$logOp);         
                $res= $this->model->getRow($sql,$critPholder,$values);                                        
            }//end of if
        }//end of for
        return $res;
    }//end of function

    public function getRowInOrder($table,$values,$fields,$critFdx,$logOp,$whichCol,$order,$limit){
        $critPholder = $this->genCritPholders($critFdx);
        for($i=0;$i<$this->tableNamesLen;$i++){
            if($this->tableNames[$i] == $table){                
                $sql = $this->builder->getRowInOrderBuilder($table,$fields,$critFdx,$critPholder,$logOp,$whichCol,$order,$limit);
                $res= $this->model->getRow($sql,$critPholder,$values);                                        
            }//end of if
        }//end of for
        return $res;
    }//end of function

    public function getAllRows($table,$fdx){                
        for($i=0;$i<$this->tableNamesLen;$i++){
            if($table == $this->tableNames[$i]){
                if($fdx != null){
                    $fields = $fdx;
                } else{
                    $fields = $this->fields[$i];
                }   
                $sql = $this->builder->getAllRowsBuilder($this->tableNames[$i],$fields);
                $res = $this->model->getAllRows($sql);   
            }//end of if            
        }//end of for
        return $res;
    }//end of function

    // getLimitRowsBuilder($table,$fields);
    public function getAllLimitRows($table,$fdx,$limit,$offset){                
        for($i=0;$i<$this->tableNamesLen;$i++){
            if($table == $this->tableNames[$i]){
                if($fdx != null){
                    $fields = $fdx;
                } else{
                    $fields = $this->fields[$i];
                }   
                $sql = $this->builder->getAllLimitRowsBuilder($this->tableNames[$i],$fields);                
                $res = $this->model->getAllLimitRows($sql,$limit,$offset);   
            }//end of if            
        }//end of for
        return $res;
    }//end of function

    public function getCustomAddNewRow($table,$values){
        for($i=0;$i<$this->tableNamesLen;$i++){
            if($table == $this->tableNames[$i]){
                $sql = $this->builder->customAddNewRowBuilder($this->tableNames[$i],$this->fields[$i],$values);
                $res = $this->model->getCustomSqlExec($sql);   
            }//end of if            
        }//end of for      
    }//end of function 
    
    public function getSearch($table,$values,$fields,$critFdx,$logOp,$ordBy,$ord,$lmStart,$lmLen){
        $critPholder = $this->genCritPholders($critFdx);
        for($i=0;$i<$this->tableNamesLen;$i++){
            if($this->tableNames[$i] == $table){
                $sql = $this->builder->getSearchBuilder($table,$fields,$critFdx,$critPholder,$logOp,$ordBy,$ord,$lmStart,$lmLen);
                $res= $this->model->getSearch($sql,$critPholder,$values);                                        
            }//end of if
        }//end of for
        return $res;
    }//end of function

    public function getJoinedRow($tables,$values,$tbl1Fields,$tbl2Fields,$fieldToJoinOn,$criteriaTable,$critFdx,$logOp,$orderBy,$order,$lim){
        for($i=0;$i<$this->tableNamesLen;$i++){
            if($this->tableNames[$i] == $tables[0] /*|| $this->tableNames[$i] == $tables[1]*/){
            $critPholder = $this->genCritPholders($critFdx);
            $sql = $this->builder->getJoinedRowBuilder($tables,$tbl1Fields,$tbl2Fields,$fieldToJoinOn,$criteriaTable,$critFdx,$critPholder,$logOp,$orderBy,$order,$lim);            
            $res = $this->model->getRow($sql,$critPholder,$values);            
            }
        }//end of for loop
        return $res;    
    }//end of function 

    public function createAuthTable($sql){
        return $res = $this->model->getCustomSqlExec($sql);
    }
}//end of class

