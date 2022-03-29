<?php ini_set('display_errors', 1); ?>
<?php require_once('helpers/auth.php'); ?>
<!DOCTYPE html>
<html lang="en">
<?php require_once("../globals/html_head.php"); ?>

<body class="grey lighten-3">
    <div class="az-header">
        <div class="container">
            <?php require_once('../globals/logo.php'); ?>
            <?php require_once('../globals/topnav_sidenav.php'); ?>
            <?php require_once('../globals/right_nav_item.php'); ?>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <?php require("views/bridge/bridge.php"); ?>
        </div>

    </div>
    <?php require_once("../globals/psd_modal.php"); ?>
    <?php require_once("views/dashboard/profile_modal.php");?>   
    <?php require_once("../globals/includes.php"); ?>    
    <script type="module" src="js/custom/bridge.js"></script>
    <script type="module" src="../js/custom/global_actions.js"></script>
</body>

</html>