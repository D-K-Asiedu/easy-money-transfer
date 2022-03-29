<?php
//  phpinfo();
interface Connection {
    public function connect();
}
Abstract Class DBConnector implements Connection
{
    private $host = "127.0.0.1";
    private $username = "root";   
    private $password = "";
    private $dbname = "easy";
    
    private static $conn = null;
   
    private function getConnect() {
        try 
        {
            $conn = new PDO("mysql:host=".$this->host.";dbname=".$this->dbname.";charset=utf8", $this->username, $this->password);            
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);            
            //persisting connection
            $conn->setAttribute(PDO::ATTR_PERSISTENT,true);
            //To ensure parametrized queries 
            $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            //var_dump($conn);
           return $conn;
        }
        catch(PDOException $e)
        {
            echo "Connection failed: " . $e->getMessage();
        }
    }//connect 
    
    public function connect(){
        if(DBConnector::$conn ==null){
            DBConnector::$conn = $this->getConnect();
        }
        return DBConnector::$conn;
    }
   
 
}//class

?>