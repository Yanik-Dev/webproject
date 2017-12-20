<?php
$title = "Reset Password";
include '../includes/header.php';
include '../includes/public-layout.php';

$isValid = true;
if(isset($_GET['token'])){

    $user = UserService::findByToken($_GET['token']);
    if(!isset($user['id'])){ $isValid = false; }
}
if(!$isValid) { 
    header('Location: ./login.php'); 
}

?>


  <div class="ui middle aligned center aligned grid" id="loginModule">
    <div class="column">
      <h2 class="ui teal image header">
      </h2>
      <form class="ui large form login-form" method="post" action="./actions/change-password.php?id=<?=$user['id'];?>">
        <div class="ui stacked segment">
        <h3 class="ui header"> Reset Your Password</h3>
          <div class="field">
            <div class="ui left icon input">
              <i class="lock icon"></i>
              <input type="password" name="password" placeholder="New Password">
            </div>
          </div>
          <div class="field">
            <div class="ui left icon input">
              <i class="lock icon"></i>
              <input type="password" name="confirmPassword" placeholder="Confirm Password">
            </div>
          </div>
         
          <input type="hidden" name="token" value="<?=$crsfToken?>">
          <button type="submit"  class="ui fluid large teal button">Reset</button>
        </div>
        <div class="ui error message"></div>
        <div class="ui negative message hide-element" id="error-msg">
        </div>
      </form>

    </div>
  </div>


</body>
<?php include './../includes/footer.php'?>
</html>