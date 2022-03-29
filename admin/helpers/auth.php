
<?php
try{
  if(!empty(isset($_POST['token']))){
      require_once("../controllers/db_controller.php");
      require_once("../helpers/jwt.php");
      $main = new Main(new SqlStringBuilder(),new Model());
      $token = $_POST['token'];      
      $accessibleFiles = Auth::authAdmin($token,$main);//returns list of files the user has access to      
    }else{
      header("Location: login");
      die("Resource is protected. Please login");    
    }
}catch(Exception $e){
  die("An error occured");
}
?>