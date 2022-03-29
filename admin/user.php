<?php 
// ini_set('display_errors', 1);
// require_once('helpers/auth.php'); 
?>

<!DOCTYPE html>
<html lang="en">
<?php require_once("../globals/html_head.php"); ?>
<?php require_once("globals/html_head.php"); ?>

<body class="grey lighten-4">
    <div class="az-header">
        <div class="container">
            <?php require_once('../globals/logo.php'); ?>
            <?php require_once('../globals/topnav_sidenav.php'); ?>
            <?php require_once('../globals/right_nav_item.php'); ?>
        </div>
    </div>

    <div class="az-content pd-y-20 pd-lg-y-30 pd-xl-y-40">
        <div class="container">
            <div class="az-content-left az-content-left-components">
                <div class="component-item">
                    <label>Transactions</label>
                    <nav class="nav flex-column">
                        <a class="nav-link new_txn" href="./#txn">New Transaction</a>
                        <a class="nav-link new_txn" href="./#config">Configure</a>
                        <!-- <a class="nav-link event_list" data-toggle="modal" data-target="#event_modaln">Event List</a> -->
                    </nav>
                    <label>Content</label>
                    <nav class="nav flex-column">
                        <a class="nav-link new_content" href="./#content">New Content</a>
                        <a class="nav-link content_list" data-toggle="modal" data-target="#content_modalm">Content List</a>
                    </nav>                   
                </div>
            </div>

            <div class="az-content-body pd-lg-l-40 d-flex flex-column">
                <div class="row row-sm">
                    <div class="col-lg">
                        <div id="app"></div>                       
                    </div>                  
                </div>
            </div>

        </div>
    </div>

    <?php require_once("views/user/card_img_preview.php") ?>
    <?php require_once("views/user/content_gen_modal.php") ?>
    <?php require_once("views/user/img_preview.php") ?> 
    <?php require_once("views/user/content_modal.php") ?>    
    <?php require_once("views/user/event_modal.php") ?>
    <?php require_once("../globals/includes.php"); ?>
    <!-- <script type="text/javascript" src="js/custom/auto_logout.js"></script> -->
   
    
    <script>        
        let fragment = location.hash.substr(1);
        let url = `views/user/${fragment}.php`;
        fetch(url).then(response => response.text()).then(data => $('#app').html(data));

        $(window).on('hashchange', function(e) {
            let fragment = location.hash.substr(1);
            let url = `views/user/${fragment}.php`;
            fetch(url).then(response => response.text()).then(data => $('#app').html(data));
        });      
    </script>
</body>

</html>