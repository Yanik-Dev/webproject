let LoginModule = (function(){
    //local variables
    let isEmailUnique = false;
    //cache DOM
    let $el = $('#loginModule');
    let $loginForm = $('.ui.form.login-form');
    let $errorMsg = $el.find('#error-msg');
    let $forgotPasswordButton = $el.find('#forgotPasswordBtn');
    let $resetPasswordModal = $('.password-modal');
    let $resetPasswordForm = $('.password-form');
    let $checkingEmailLoader = $(".mini.text.inline.loader");
    let $existErrorMsg = $('#exist-msg');
    let $emailInput = $resetPasswordForm.find('#email');

    //bind events
    $loginForm.on('submit', _authenticate);
    $resetPasswordForm.on('submit', _reset);
    $forgotPasswordButton.on('click', ()=>{
        $resetPasswordModal.modal('show');
    });
    $emailInput.on('blur', ()=>{
        _checkIfEmailExist();
    })
    
    //init
    $loginForm.form({
        on: 'blur',
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
          password: {
            identifier: 'password',
            rules: [
              {
                type   : 'empty',
                prompt : 'Password cannot be empty'
              }
            ]
          },
        }
    });
    
    $resetPasswordForm.form({
        on: 'blur',
        inline:true,
        fields: {
          email: {
            identifier: 'email',
            rules: [
              {
                type   : 'email',
                prompt : 'Please enter a valid email'
              }
            ]
          }
        }
    });

    //event handlers
    function _authenticate(event){
        if( !$loginForm.form('is valid') ){
            $errorMsg.hide();
            return;
        }
        $.ajax({
            url: './actions/login.php',
            dataType: 'text',
            type: 'post',
            contentType: 'application/x-www-form-urlencoded',
            data: $loginForm.serialize(),
            success: _onLogin,
            error: _onError
        });
        event.preventDefault();
    }

    function _onLogin(data){
        console.log(data)
        let result = JSON.parse(data);
        if(result.status == 200){
            $errorMsg.hide();
            location.href=".././index.php";
        }else{
            $errorMsg.text("Incorrect email or password");
            $errorMsg.show();  
        }
    }

    function _onError( jqXhr, textStatus, errorThrown ){
        console.log( errorThrown );
        $errorMsg.text("An unexpected error as occured.");
        $errorMsg.show();
    }


    function _checkIfEmailExist(){
        if( !$resetPasswordForm.form('is valid', "email") ){
          return;
        }
        $existErrorMsg.hide();
        $checkingEmailLoader.addClass("active");
        $.ajax({
          url: './actions/register.php?email='+$emailInput.val(),
          dataType: 'text',
          type: 'get',
          contentType: 'application/x-www-form-urlencoded',
          success: function(data){
            $checkingEmailLoader.removeClass("active");
            console.log(data)
            let result = JSON.parse(data);
            if(!result){
              $existErrorMsg.show();
              isEmailUnique = false;
            }else{
              isEmailUnique = true;
            }
          },
          error: _onError
        });
    }

    function _reset(){
      event.preventDefault();
      if( !$resetPasswordForm.form('is valid')  || isEmailUnique){
          return;
      }
      $.ajax({
          url: './actions/register.php',
          dataType: 'text',
          type: 'post',
          contentType: 'application/x-www-form-urlencoded',
          data: $resetPasswordForm.serialize(),
          success: (data)=>{
            
          },
          error: _onError
      });
    }
  

})();

