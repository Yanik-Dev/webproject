
<?php 
 $title = "Register";
 include '../includes/header.php';
 include '../includes/public-nav.php';
?>
<div class="ui middle aligned center aligned grid">
  <div class="column">
    <div class="ui warning form segment">
     <!-- <div class="ui warning message">
        <div class="header">Could you check something!</div>
        <ul class="list">
          <li>You forgot your <b>first name</b></li>
          <li>And also your <b>last name</b></li>
        </ul>
      </div>-->
      <div class="two error fields">
        <div class="field">
          <input placeholder="First Name" type="text" name="firstname">
        </div>
        <div class="field">
          <input placeholder="Last Name" type="text" name="lastname">
        </div>
      </div> 
      <div class="field">
        <input placeholder="Email" type="email" name="email">
      </div>
      <div class="field">
        <input type="password" placeholder="First Name" name="password">
      </div>
      <div class="field">
        <input type="password" placeholder="Confirm Password" name="confirmPassword">
      </div>
      <div class="inline field">
        <div class="ui checkbox">
          <input type="checkbox" />
          <label>I agree to the <a href="#">Terms and Conditions</a></label>
        </div>
      </div>
      <div class="ui teal submit button">Submit</div>
    </div>
  </div>
  </div>