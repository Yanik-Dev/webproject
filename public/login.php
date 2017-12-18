<?php 
$title = "Login";
include '../includes/header.php';
include '../includes/public-layout.php';
?>
  <div class="ui middle aligned center aligned grid" id="loginModule">
    <div class="column">

      <h2 class="ui teal image header">
        <div class="content ">
          <img style="width: 150px;" src="./assets/img/blookup-logo.png" alt="">
        </div>
      </h2>
      <form class="ui large form login-form">
        <div class="ui stacked segment">
        <h3 class="ui header"> Sign into your account</h3>
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
          <div class="field" style="float:left">
            <div class="ui checkbox">
              <input type="checkbox" name="rememberMe" value="yes">
              <label>Remember me</label>
            </div>
          </div>
          <a  href="#" id="forgotPasswordBtn" style="float:right; color: #111"><i class="lock icon"></i> Forgot Password?</a> 
          <input type="hidden" name="token" value="<?=$crsfToken?>">
          <button type="submit"  class="ui fluid large teal button">Login</button>
        </div>
        <div class="ui error message"></div>
        <div class="ui negative message hide-element" id="error-msg">
        </div>
      </form>

    </div>
  </div>

  <!-- Modals -->

  <div class="ui mini modal password-modal">
    <div class="header">Request Password Change</div>
    <div class="content">
      <form class="ui large form password-form" method="POST" >
        <div class="field">
          <label>Email</label>
          <input type="email" id="email" placeholder="email@example.com" name="email"> 
          <span class="ui mini text inline loader"></span>
          <p class="ui red hide-element" style="color: red" id="exist-msg"><i class="close icon"></i>Email does not exist. <a href="./register.php">Create Account?</a></p>
        </div>
        <input type="hidden" name="token" value="<?=$crsfToken?>">
        <button type="submit" class="ui teal button">Submit</button>
      </form>
    </div>
  </div>

<!-- ./Modals -->
</body>
<?php include './../includes/footer.php'?>
<script src="./assets/js/login.module.js"></script>
</html>