
<?php 
 $title = "Register";
 include '../includes/header.php';
 include '../includes/public-layout.php';
?>
  <div class="ui middle aligned centered aligned grid" id="registrationModule">
    <div class="column">

    <form class="ui large form" method="post">
      <div class="ui warning segment">
        <div class="ui inverted dimmer">
          <div class="ui text loader">Setting up your account...</div>
        </div>
        <div class="two fields">
          <div class="field">
            <label>First name</label>
            <input placeholder="First Name" type="text" name="firstname">
          </div>
          <div class="field">
            <label>Last name</label>
            <input placeholder="Last Name" type="text" name="lastname">
          </div>
        </div> 
        <div class="inline fields">
          <label for="">Select Gender: </label>
          <div class="field">
            <div class="ui radio checkbox">
              <input type="radio" name="gender" checked="" value="Male" tabindex="0" class="hidden">
              <label>Male</label>
            </div>
          </div>
          <div class="field">
            <div class="ui radio checkbox">
              <input type="radio" name="gender" value="Female" tabindex="0" class="hidden">
              <label>Female</label>
            </div>
          </div>
        </div>
        <div class="field">
            <label>Email</label>
            <input placeholder="Email" type="email" name="email" id="email">
        </div>
        <span class="ui mini text inline loader"></span>
        <p class="ui red hide-element" style="color: red" id="exist-msg"><i class="close icon"></i>Email already exist. <a href="">Forgot your Password?</a></p>
        <div class="field">
          <label>Password</label>
          <input type="password" placeholder="Password" name="password">
        </div>
        <div class="field">
          <label>Confirm Password</label>
          <input type="password" placeholder="Confirm Password" name="confirmPassword">
        </div>
        <input type="hidden" name="token" value="<?=$crsfToken;?>">
        <div class="inline field">
          <div class="ui checkbox">
            <input type="checkbox" id="terms-checkbox" value="accept"/>
            <label>I agree to the <a href="./terms.php">Terms and Conditions</a></label>
          </div>
        </div>
        <button type="submit" class="ui teal button" disabled>Submit</button>
      </div>

      <div class="ui error message"></div>
      <div class="ui negative message hide-element" id="error-msg"></div>
      </form>
    </div>
  </div>
</body>
<?php include './../includes/footer.php'?>
<script src="./assets/js/registration.module.js"></script>