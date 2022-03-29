<!DOCTYPE html>
<html lang="en">
<?php require_once('../globals/html_head.php'); ?>

<body class="az-body">

  <div class="az-signin-wrapper">
    <div class="az-card-signin">
      <h1 class="az-logo"><img width="110" height="110" class="img-fluid" src="../resources/img/brand2.png" /></h1>
      <div class="az-signin-headerx text-school-blue mb-5">
        <h2>Admin Login</h2>
        <!-- <h4>Please sign in to continue</h4> -->

        <section>
          <div class="form-group">
            <label>Username</label>
            <input type="text" class="form-control inputs" id="username" placeholder="Enter your username">
          </div>
          <div class="form-group">
            <label>Password</label>
            <input type="password" class="form-control inputs" id="password" placeholder="Enter your password">
          </div>
          <button class="btn btn-az-primary my_pink_btn" id="login">            
            <span class="spinner-border spinner-border-sm d-none" id="login_spinner" role="status" aria-hidden="true"></span>
            Sign In
          </button>
        </section>
      </div>
      <div class="az-signin-footer">
        <p><a href="#">Forgot password?</a></p>        
      </div>
    </div>
  </div>
  <?php require_once('../globals/includes.php') ?>
  <script type="module" src="js/custom/login.js"></script>
</body>

</html>