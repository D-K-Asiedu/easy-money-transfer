<style>

.wrimagecard{	
	margin-top: 0;
    margin-bottom: 1.5rem;
    text-align: left;
    position: relative;
    background: #fff;
    box-shadow: 3px 5px 207px 0px rgba(46,61,73,0.15);
    border-radius: 4px;
    transition: all 0.3s ease;
}

.wrimagecard .fa{
	position: relative;
    /* font-size: 70px; */
}

.wrimagecard-topimage_header{
padding: 20px;
}

a.wrimagecard:hover, .wrimagecard-topimage:hover {
    box-shadow: 2px 4px 8px 0px rgba(46,61,73,.5);
}

.wrimagecard-topimage_title {
    padding: 20px 24px;
    height: 80px;
    padding-bottom: 0.75rem;
    position: relative;
}

.wrimagecard-topimage a {
    border-bottom: none;
    text-decoration: none;
    color: #525c65;
    transition: color 0.3s ease;
} 
</style>


<div id="icons" class="container-fluid pt-5  col-md-12 mx-auto mt-5">    
<div class="row">
    <?php 
    // col-xl-5 col-lg-6 col-md-10    
        require_once("../helpers/utilities.php");
        $res = $main->getAllRows("icons",["name"]);
        $fa = [];
        for($i=0;$i<count($res);$i++){
            $fa []= $res[$i]["name"];
        }
        
        for($i=0;$i<count($accessibleFiles);$i++){
            $color = '#' . substr(str_shuffle('ABCDEF0123456789'), 0, 6);
            echo '
            <div class="col-md-2">
            <div class="wrimagecard wrimagecard-topimage">
                <a href="'.$accessibleFiles[$i]["path"].'" class="direct">
                <div class="wrimagecard-topimage_header" style="background-color: '.bgColor().'">
                  <center><i class = "'.$fa[rand(0,count($fa)-1)].' fa-4x" style="color:'.$color.'"></i></center>
                </div>
                <div class="wrimagecard-topimage_title text-center">
                  <label>'.$accessibleFiles[$i]["description"].'
                  <div class="pull-right badge" id="WrControls"></div></label>
                </div>
              </a>
            </div>
          </div>';
        }
        // die();
    ?>    

</div>
</div>