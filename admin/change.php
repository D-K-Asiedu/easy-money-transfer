<style>
    .card-header {
        margin-top: -4rem;
        background: #0a0082 !important;
        border-radius: 5px !important;
        box-shadow: 0 5px 20px 0 rgba(0, 0, 0, .18), 0 4px 9px 0 rgba(0, 0, 0, .15);
    }

    .card-body {
        box-shadow: 0 5px 20px 0 rgba(0, 0, 0, .5), 0 4px 9px 0 rgba(0, 0, 0, .5);
    }

    .btn {
        border-radius: 2em !important;
    }

    .md-form {
        margin-top: 40px !important;
    }

    /* .login-body{
        background: linear-gradient(to bottom, #c3d8ec -3%, rgba(10,0,130,0.8),rgba(222,80,151,0.7)),url("img/campus-bg.jpg") no-repeat center center fixed;               
        background-size:cover;        
    } */
</style>

<?php require_once('helpers/auth.php'); ?>

<!DOCTYPE html>
<html lang="en">
<?php require_once("globals/html_head.php"); ?>
<?php require_once("../globals/html_head.php"); ?>

<body class="grey lighten-3 login-body">
    <div class="az-header">
        <div class="container">
            <?php require_once('../globals/logo.php'); ?>
            <?php require_once('../globals/topnav_sidenav.php'); ?>
            <?php require_once('../globals/right_nav_item.php'); ?>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <?php require_once("views/change/fields.php"); ?>
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->

    <?php require_once("../globals/includes.php"); ?>
    <?php require_once("globals/includes.php"); ?>
    <script type="module" src="js/custom/change.js"></script>



</body>

</html>