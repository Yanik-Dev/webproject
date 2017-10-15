<?php 
$title = "Login";
include '../includes/header.php';
include '../includes/public-nav.php';?>

<div class="ui middle aligned center aligned grid" id="loginModule">
  <div class="column">
    <h2 class="ui teal image header">
      <div class="content">
        Login to your Account
      </div>
    </h2>
    <form class="ui large form" id="login-form" method="post" action="../actions/login.php">
      <div class="ui stacked segment">
        <div class="field">
          <div class="ui left icon input">
            <i class="user icon"></i>
            <input type="text" name="email" placeholder="E-mail address">
          </div>
        </div>
        <div class="field">
          <div class="ui left icon input">
            <i class="lock icon"></i>
            <input type="password" name="password" placeholder="Password">
          </div>
        </div>
        <div class="field">
          <div class="ui checkbox">
            <input type="checkbox" name="example">
            <label>Keep me signed in</label>
          </div>
        </div>
        <button type="submit" id="authenticate-button" class="ui fluid large teal submit button">Login</button>
      </div>
      <div class="ui error message"></div>
    </form>

    <div class="ui message">
    <a href="#">Forgot Password</a>|<a href="#">Sign Up</a>
    </div>
  </div>
</div>

</body>

</html>
<script src="./assets/js/login.module.js"></script>