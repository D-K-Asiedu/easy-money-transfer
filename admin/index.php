<?php
// ini_set('display_errors', '1');
    require_once("../controllers/router.php");
    $r = new Route('/easy/admin');   

    $r->goto('/',function(){                
        require_once('user.php');        
    });

    $r->goto('/user',function(){                
        require_once('user.php');        
    });

    $r->goto('/login',function(){                
        require_once('login.php');        
    });     
    $r->run();
?>