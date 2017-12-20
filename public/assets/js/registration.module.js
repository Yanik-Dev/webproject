let RegistrationModule = (function(){
    //local variables
    let isEmailUnique = false;

    //cache DOM
    let $el = $('#registrationModule');
    let $registrationForm = $el.find('.ui.form');
    let $errorMsg = $el.find('#error-msg');
    let $existErrorMsg = $el.find('#exist-msg');
    let $submitBtn = $el.find('.ui.button');
    let $termsBtn = $el.find('#terms-checkbox');
    let $emailInput = $el.find('#email');
    let $checkingEmailLoader = $el.find(".mini.text.inline.loader");
    let $registerLoader = $el.find(".ui.inverted.dimmer");
    
    //bind events
    $registrationForm.on('submit', _register);
    $termsBtn.on('click', _toggleSubmitBtn);
    $emailInput.bind('blur', _checkIfEmailExist); 
    
    //init
    $('.ui.radio.checkbox').checkbox();
    $registrationForm.form({
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
            },
            firstname: {
              identifier: 'firstname',
              rules: [
                {
                  type   : 'minLength[2]',
                  prompt : 'First name must be at least {ruleValue} characters'
                }
              ]
            },
            lastname: {
              identifier: 'lastname',
              rules: [
                {
                  type   : 'minLength[2]',
                  prompt : 'Lastname must be at least {ruleValue} characters'
                }
              ]
            },
            gender: {
              identifier: 'gender',
              rules: [
                {
                  type   : 'empty',
                  prompt : 'Please select a gender'
                }
              ]
            },
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

    //event hanlders 
    function _toggleSubmitBtn(){
      $submitBtn[0].disabled = !$termsBtn[0].checked;
     
    }

    function _register(event){
        event.preventDefault();
        if( !$registrationForm.form('is valid')  || !isEmailUnique){
            $errorMsg.hide();
            return;
        }
        $registerLoader.addClass("active");
        $.ajax({
            url: './actions/register.php',
            dataType: 'text',
            type: 'post',
            contentType: 'application/x-www-form-urlencoded',
            data: $registrationForm.serialize(),
            success: _onSuccess,
            error: _onError
        });
    }

    function _onSuccess(data){
           $errorMsg.hide();
           location.href=".././success.php";
    }

    function _onError( jqXhr, textStatus, errorThrown ){
        console.log( errorThrown );
        $errorMsg.text("An unexpected error as occured.");
        $errorMsg.show();
        $registerLoader.removeClass("active");
    }

    function _checkIfEmailExist(event){
      if( !$registrationForm.form('is valid', "email") ){
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

})();