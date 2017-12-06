
<?php 
 $title = "Account";
 include '../includes/header.php';
 include '../includes/admin-layout.php';

$token = SecurityService::generateToken("crsf_token");

?>

<div class="ui middle aligned centered aligned grid" id="registrationModule">
  <div class="column">

  <br />
  <br />
  <div class="ui special cards">
    <div class="ui centered card">
        <div class="blurring dimmable image">
        <div class="ui dimmer">
            <div class="content">
            <div class="center">
                <div class="ui inverted button change-account-details" style="margin-bottom:10px"><i class="large user icon"></i>Change Account Details</div>
                <div class="ui inverted button change-password-btn"><i class="large lock icon"></i>Change Password</div>
            </div>
            </div>
        </div>
        <img src="<?= ($session->getImage() !== null )?'./uploads/'.$session->getImage(): './assets/img/img-wireframe.png'?>">
        </div>
        <div class="content">
            <div class="header"><?= strtoupper($session->getFirstname()).' '.strtoupper($session->getLastname())?></div>
            <div class="meta" id="email-content"> <a>Email: <?= $session->getEmail()?></a> </div>
        </div>
        <div class="extra content">
            <span class="right floated">
                Joined in <?=date('Y', strtotime($session->getDateCreated()))?>
            </span>
            <span>
                <i class="building icon"></i>
                <?= count(BusinessService::findAll($session->getUserId())) ?> Businesses
            </span>
        </div>
    </div>
  </div>


  <form class="ui large form account-details hide-element" 
        action="./actions/account.php?option=changeinformation" 
        method="post" >
    <div class="ui warning segment">
      <div class="ui inverted dimmer">
        <div class="ui text loader">Saving...</div>
      </div>
      <div  class="ui small image" style="margin-left: 30%">
        <img src="<?= ($session->getImage() !== null )?'./uploads/'.$session->getImage(): './assets/img/img-wireframe.png'?>">
        <div class="mini ui icon circular button" style="position: absolute; top:5px;right: 3px;">
            <i class="large upload icon"></i> 
            <input class="file-input"  type="file" name="file">
        </div>
      </div>
      <div class="ui horizontal divider"> </div>
      <div class="two fields">
        <div class="field">
          <label>First name</label>
          <input placeholder="First Name" type="text" name="firstname" value="<?=$session->getFirstname()?>">
        </div>
        <div class="field">
          <label>Last name</label>
          <input placeholder="Last Name" type="text" name="lastname" value="<?=$session->getLastname()?>">
        </div>
      </div> 
      <div class="field">
          <label>Email</label>
          <input placeholder="Email" type="email" name="email" id="email" value="<?=$session->getEmail()?>">
      </div>
      <span class="ui mini text inline loader"></span>
      <p class="ui red hide-element" style="color: red" id="exist-msg"><i class="close icon"></i>Email already exist. <a href="">Forgot your Password?</a></p>

      <input type="hidden" name="token" value="<?=$token?>">

      <button type="submit" class="ui teal button">Save Changes</button>
      <a href="./account.php" class="ui inverted red button">Cancel</a>
    </div>
   </form>
   <div class="ui uploading inverted dimmer">
      <div class="ui indeterminate text loader">Uploading</div>
    </div>
    <div class="ui mini bottom attached error message hide-element">
      <i class="close icon"></i>
      <span></span>
    </div>
  </div>
</div>

<!-- Modals -->

<div class="ui mini modal password-modal">
  <div class="header">Change Password</div>
  <div class="content">
    <p>Once your password is changed you will required to login.</p>
  <form class="ui large form password-form" action="./actions/account.php?option=changepassword" method="POST" >
    <input type="hidden" name="firstname" value="<?=$session->getFirstname()?>">
    <input type="hidden" name="lastname" value="<?=$session->getLastname()?>">
    <input type="hidden" name="email" id="email" value="<?=$session->getEmail()?>">
    <div class="field">
      <label>Password</label>
      <input type="password" placeholder="Password" name="password">
    </div>
    <div class="field">
      <label>Confirm Password</label>
      <input type="password" placeholder="Confirm Password" name="confirmPassword">
    </div>
    <input type="hidden" name="token" value="<?=$token?>">
    <button type="submit" class="ui teal button">Submit</button>
  </div>

   <div class="ui negative message hide-element" id="error-msg">
  </div>
  </form>
  </div>
</div>

<!-- ./Modals -->
<script>
 
    $('.special.cards .image').dimmer({ on: 'hover'});
    let $changePasswordBtn = $('.change-password-btn');
    let $changeAccountDetailsBtn = $('.change-account-details');
    let $detailsCard = $('.special.cards');
    let $detailsForm = $('.form.account-details');
    let $passwordForm = $('.form.password-form');
    let $emailInput = $detailsForm.find('#email');
    let $existErrorMsg = $detailsForm.find('#exist-msg');
    let $changePasswordModal = $('.ui.modal.password-modal');

    //event binding
    $changePasswordBtn.on('click', ()=>{
        $changePasswordModal.modal('show')
    });
    $changeAccountDetailsBtn.on('click',()=>{
        $detailsCard.hide();
        $detailsForm.show();
    })
    $detailsForm.on('submit', function(ev){
        if( !$detailsForm.form('is valid')  || !isEmailUnique){
            ev.preventDefault();
            $errorMsg.hide();
            return;
        }
    })

    $('.file-input').on('change',_onUpload);
    $emailInput.bind('blur', _checkIfEmailExist); 
    
    //init
    $detailsForm.form({
        on: 'blur',
        inline: true,
        fields: {
            email: {
              identifier: 'email',
              rules: [
                {
                  type   : 'email',
                  prompt : 'Please enter a valid email'
                }
              ]
            },
            firstname: {
              identifier: 'firstname',
              rules: [
                {
                  type   : 'minLength[2]',
                  prompt : 'Your firstname must be at least {ruleValue} characters'
                }
              ]
            },
            lastname: {
              identifier: 'lastname',
              rules: [
                {
                  type   : 'minLength[2]',
                  prompt : 'Your lastname must be at least {ruleValue} characters'
                }
              ]
            },
        }
    });
    $passwordForm.form({
        on: 'blur',
        inline: true,
        fields: {
            password: {
              identifier: 'password',
              rules: [
                
                {
                  type   : "minLength[8]",
                  prompt : 'Password should have at least 8 characters'
                }
              ]
            },
           confirmPassword: {
            identifier: 'confirmPassword',
            rules: [
              {
                type   : 'match[password]',
                prompt : 'Please put the same value in both fields'
              }
            ]
          },
        }
    });
    //events handlers  
    function _onUpload(){

        let imgPath = $(this)[0].value;
        let $this = $(this);
        let $container = $this.closest('div.account-details')
        let $dimmer = $container.find('.ui.uploading.dimmer');
        let $detailErrorMsg = $container.find('.mini.bottom.attached.error.message');
        let ext = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();

        $detailErrorMsg.addClass("hide-element");
        if ($this[0].files[0].size > 2000000 || $this[0].files[0].fileSize > 2000000){
            $detailErrorMsg.find('span').text("File cannot exceed 2MB.");
            $detailErrorMsg.removeClass("hide-element");
            return;
        }
        if (ext == "gif" || ext == "png" || ext == "jpg" || ext == "jpeg") {
            if (typeof (FileReader) == "undefined") {
                $detailErrorMsg.find('span').text("This browser does not support FileReader.");
                $detailErrorMsg.removeClass("hide-element");
                return;
            }
        } else {
            $detailErrorMsg.find('span').text("Only GIF, PNG, JPG, JPEG are supported."); 
            $detailErrorMsg.removeClass("hide-element");
            return;     
        }
        
        let imageViewer = $this.closest('.ui.small.image').find('img');
        let file = $this[0].files[0];
        let reader = new FileReader();
        reader.onload = function (e) {
            
            formData = new FormData();
            formData.append('file', file);
            $dimmer.addClass('active');
            $dimmer.find(".text.loader").text('Uploading');
            $.ajax({
                url: './actions/account.php',
                type: 'POST',
                dataType: 'text',
                contentType: false,
                processData: false,
                data: formData,
                success:function(data){
                    let result = JSON.parse(data);
                    window.location.href="./account.php";
                    /*let image = 'assets/img/img-wireframe.png';
                    if(result){
                        image = e.target.result;  
                    }
                    imageViewer.attr('src', image); 
                    $detailsCard.find('img').attr('src', image)
                    $dimmer.removeClass('active');*/
                },
                error: ()=>{

                },
                xhr: function () {
                    let xhr = $.ajaxSettings.xhr();
                    xhr.upload.onprogress = function (e) {
                        let percent = Math.floor(e.loaded / e.total * 100);  
                        $dimmer.find('.text').text('uploading '+percent+'%')                              
                       
                    };
                    return xhr;
                }
            })
        }
        reader.readAsDataURL($(this)[0].files[0]);
    }

    function _checkIfEmailExist(event){
      if( !$detailsForm.form('is valid', "email") ){
        return;
      }
      if($emailInput.val() == $('.special.cards .image').find('#email-content')){

          isEmailUnique = true;
          return;
      }
      $existErrorMsg.hide();
      $checkingEmailLoader.addClass("active");
      $.ajax({
        url: './actions/register.php?email='+$emailInput[0].value,
        dataType: 'text',
        type: 'get',
        contentType: 'application/x-www-form-urlencoded',
        success: function(data){
          $checkingEmailLoader.removeClass("active");
          let result = JSON.parse(data);
          if(result){
            $existErrorMsg.show();
            isEmailUnique = false;
          }else{
            isEmailUnique = true;
          }
        },
        error: _onError
      });
    }
</script>