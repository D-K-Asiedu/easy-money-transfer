<?php 
$dbconnectorPath =dirname(__FILE__,2)."/config/dbconnector.php";
require_once($dbconnectorPath);

interface InterfaceModel{
    public function insert($sql,$place_holders,$values);
}

class Model extends DBConnector implements InterfaceModel{
    private $conn;
    public function __construct(){
        $this->conn = $this->connect();
      
    }

    public function insert($sql,$place_holders,$values){        
        $stmt = $this->conn->prepare($sql); 
        $place_holders_len = count($place_holders);
        $values_len = count($values);
        // print_r($values);
        // print_r($place_holders);
        // echo  $sql;
        if($place_holders_len == $values_len){
            for($j=0;$j<$values_len;$j++){
                $stmt -> bindParam($place_holders[$j],$values[$j]);
            } 
            if($stmt->execute($values)){
                return 1;
            }else{
                return 0;
            }   
        } else{
            echo "place holders length does not match values length";
        }            
                     
    }//end of function 

    public function getTableNames(){
        $query = $this->conn->prepare("SHOW TABLES");
        $query->execute();
        $table_names = $query->fetchAll(PDO::FETCH_COLUMN);
        return $table_names;
    }

    public function getTableFields($table){
        $query = $this->conn->prepare("DESCRIBE $table");
        $query->execute();
        $table_names = $query->fetchAll(PDO::FETCH_COLUMN);
        return $table_names;
    }
     
    public function getLastId(){
        return $this->conn->lastInsertId();
    }

    public function getAllRows($sql){
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();
        return $result;
    }//end of function

    public function getAllLimitRows($sql,$limit,$offset){
        $stmt = $this->conn->prepare($sql);
        $stmt -> bindParam(":limit",$limit);
        $stmt -> bindParam(":offset",$offset);
        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();
        return $result;
    }//end of function

    function getMultiJoinedRow($sql,$place_holders,$values,$l,$o){            
        $stmt = $this->conn->prepare($sql);
        $place_holders_len = count($place_holders);
        $values_len = count($values);

        if($place_holders_len != $values_len){
            echo "Placeholders length does not match values length";
            return;
        }                        
        for($j=0;$j<$values_len;$j++){
            $stmt -> bindParam($place_holders[$j],$values[$j]);
        }
        if($l !=null){
            $stmt -> bindParam($l[0],$l[1]); 
            if($o != null){
                $stmt -> bindParam($o[0],$o[1]);
            }
        }
        
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();                  
        
        return $result;    
    }//end of fucntion 

    public function getRow($sql,$place_holders,$values){        
        $stmt = $this->conn->prepare($sql);
        $place_holders_len = count($place_holders);
        $values_len = count($values);

        if($place_holders_len == $values_len){
            for($j=0;$j<$values_len;$j++){
                $stmt -> bindParam($place_holders[$j],$values[$j]);
            }
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetchAll();                  
           
            return $result;    
        } else{
            echo "place holders length does not match values length";
        }  

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();
        return $result;
    }//end of fucntion 

    public function getSearch($sql,$place_holders,$value){
        $stmt = $this->conn->prepare($sql);
        $place_holders_len = count($place_holders);
        // $values_len = count($values);  
        // echo $sql;      
        // if($value !="" || $value !=null){            
            for($j=0;$j<$place_holders_len;$j++){
                // if($values[$j]!=""){
                    $val = "%".$value."%";
                    $stmt->bindParam($place_holders[$j],$val);
                // }                
            }
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetchAll();                  
           
            return $result;    
        // } else{
        //     echo "place holders length does not match values length";
        // }  

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();
        return $result;
    }//end of fucntion 

    public function updateAllRows($sql,$place_holders,$values){
        $stmt = $this->conn->prepare($sql); 
        $place_holders_len = count($place_holders);
        $values_len = count($values);
      
        if($place_holders_len == $values_len){
            for($j=0;$j<$values_len;$j++){
                $stmt -> bindParam($place_holders[$j],$values[$j]);
            } 
            if($stmt->execute()){
                return 1;
            }else{
                return 0;
            }     
        } else{
            echo "place holders length does not match values length";
        }            
                    
    }
    public function updateRow($sql,$place_holders,$values,$criteria_placeholders,$criteria_values){        
        $stmt = $this->conn->prepare($sql);        
        $place_holders_len = count($place_holders);
        $values_len = count($values);       
        $criteria_placeholders_len = count($criteria_placeholders);
        $criteria_values_len = count($criteria_values);              
       if($place_holders_len == $values_len){
            if($criteria_placeholders_len != $criteria_values_len){
                echo "criteria placeholders length mismatches criteria values";
                return;
            }    
            for($j=0;$j<$values_len;$j++){
                    $stmt -> bindParam($place_holders[$j],$values[$j]);
                }                               
            for($i=0;$i<$criteria_values_len;$i++){                              
                $stmt -> bindParam($criteria_placeholders[$i],$criteria_values[$i]);
            }
                if($stmt->execute()){
                    return 1;
                }else{
                    return 0;
                }  
            } else{
                echo "place holders length does not match values length";
            }                                                   
    }

    public function deleteAllRows($sql){

    }//end of function

    public function deleteRow($sql,$criteria_placeholders,$criteria_values){
        $stmt = $this->conn->prepare($sql);        
        $criteria_placeholders_len = count($criteria_placeholders);
        $criteria_values_len = count($criteria_values);
        if($criteria_placeholders_len != $criteria_values_len){
            echo "criteria placeholders length mismatches criteria values";
            return;
        }    
        for($i=0;$i<$criteria_values_len;$i++){                              
            $stmt -> bindParam($criteria_placeholders[$i],$criteria_values[$i]);
        }
        if($stmt->execute()){
            return 1;
        }else{
            return 0;
        }                  
    }//end of function

    public function getCustomSelectSqlExec($sql){
        try{
            return $stmt = $this->conn->query($sql)->fetchAll();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }

    public function getCustomSqlExec($sql){//used for inserting and updating
        try{
            $stmt = $this->conn->exec($sql);   
            if($stmt){
                return 1;
            } else{
                return 0;
            }
        }catch(Exception $e){
            echo $e->getMessage();
        }
                       
    }
    
}//end of class


?>