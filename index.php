<?php
// ini_set('display_errors', '1');
    require_once("controllers/router.php");
    $r = new Route('/easy');     
    $r->goto('/',function(){
        header('Location: admin/');        
    });
    
    $r->run();
?>