<style>
    body {
        font-family: "Lato", sans-serif;
    }

    .sidenav {
        height: 100%;
        width: 0;
        position: fixed;
        z-index: 2000;
        top: 0;
        right: 0;
        background-color: white;
        color: black;        
        overflow-x: hidden;
        transition: 0.5s;
        padding-top: 60px;
    }

    .sidenav a {
        padding: 8px 8px 8px 32px;
        text-decoration: none;
        font-size: 12pt;
        color: #818181;
        display: block;
        transition: 0.3s;
    }

    .sidenav a:hover,
    .sidenav li:hover {
        color: #000;
        font-weight: bold;
    }

    .sidenav .closebtn {
        position: absolute;
        top: 0;
        right: 25px;
        font-size: 36px;
        margin-left: 50px;
    }

    #sidenav-overlay {
        position: fixed;
        top: 0;
        right: 0;
        left: 0;
        z-index: 2;
        height: 120vh;
        background-color: rgba(0, 0, 0, 0.5);
        will-change: opacity;
    }

    @media screen and (max-height: 450px) {
        .sidenav {
            padding-top: 15px;
        }

        .sidenav a {
            font-size: 18px;
        }
    }
</style>

<div id="mySidenav" class="sidenav">    
    <a class="navbar-brandd mx-auto mt-n5" href="#" target="_blank">        
        <img style="width:250px" src="../resources/img/brand.png">
    </a>

    <!--Accordion wrapper-->
    <div class="accordion md-accordion mt-n3 mySidenavMenu" id="accordionEx1" role="tablist" aria-multiselectable="true">
        <!-- <section class="card border-0">
            <a>Home</a>
        </section>
        <section class="card border-0">
            <a href="https://aamusted.edu.gh/application/student/login.php">Apply</a>
        </section>
        <section class="card border-0">
            <div class="card-header p-0">
                <a class="collapsed font-weight-bold" data-toggle="collapse" data-parent="#accordionEx1" href="#collapse1" aria-expanded="false">
                    About<i class="fas fa-angle-down rotate-icon"></i>
                </a>
            </div>
            <div id="collapse1" class="collapse" role="tabpanel" aria-labelledby="headingTwo" data-parent="#accordionEx1">
                <div class="card-body p-0">
                    <ul class="list-group">
                        <li class="list-group-itemx pl-2 p-0"><a>Facts About AAM-USTED</a></li>
                        <li class="list-group-itemx pl-2 p-0"><a>Campus Location</a></li>
                        <li class="list-group-itemx pl-2 p-0"><a>University Leadership</a></li>
                        <li class="list-group-itemx pl-2 p-0"><a>History & Tradition</a></li>
                        <li class="list-group-itemx pl-2 p-0"><a>Vision, Mission & Core Mandate</a></li>
                        <li class="list-group-itemx pl-2 p-0"><a>Ranking</a></li>                       
                        <li class="list-group-itemx pl-2 p-0"><a>Campuses</a></li>                       
                    </ul>
                </div>
            </div>
        </section>
        <section class="card border-0">
            <div class="card-header p-0" role="tab" id="headingTwo">
                <a class="collapsed font-weight-bold" data-toggle="collapse" data-parent="#accordionEx1" href="#collapse2" aria-expanded="false" aria-controls="collapseTwo1">
                    Academics<i class="fas fa-angle-down rotate-icon"></i>
                </a>
            </div>
            <div id="collapse2" class="collapse" role="tabpanel" aria-labelledby="headingTwo" data-parent="#accordionEx1">
                <div class="card-body p-0">
                    <ul class="list-group">
                        <li class="list-group-itemx pl-2 p-0"><a>Faculties</a></li>
                        <li class="list-group-itemx pl-2 p-0"><a>Institutes</a></li>
                        <li class="list-group-itemx pl-2 p-0"><a>Academic Deparments</a></li>
                        <li class="list-group-itemx pl-2 p-0"><a>Academic Programmes</a></li>
                        <li class="list-group-itemx pl-2 p-0"><a>Graduate School</a></li>
                        <li class="list-group-itemx pl-2 p-0"><a>Scholarships</a></li>
                        <li class="list-group-itemx pl-2 p-0"><a>Academic Calendar</a></li>
                    </ul>
                </div>
            </div>
        </section> 
        <section class="card border-0">
            <div class="card-header p-0" role="tab" id="headingTwo1">
                <a class="collapsed font-weight-bold" data-toggle="collapse" data-parent="#accordionEx1" href="#collapse3" aria-expanded="false" aria-controls="collapseTwo1">
                    Campuses<i class="fas fa-angle-down rotate-icon"></i>
                </a>
            </div>
            <div id="collapse3" class="collapse" role="tabpanel" aria-labelledby="headingTwo1" data-parent="#accordionEx1">
                <div class="card-body p-0">
                    <ul class="list-group">
                        <li class="list-group-itemx pl-2 p-0"><a>Kumasi</a></li>
                        <li class="list-group-itemx pl-2 p-0"><a>Mampong</a></li>
                    </ul>
                </div>
            </div>
        </section> 
        <section class="card border-0">
            <a>Gallery</a>
        </section>
         <section class="card border-0">
            <a>Alumni</a>
        </section> -->
    </div>
</div>

<div id="sidenav-overlay" class="" style="display:none"></div>