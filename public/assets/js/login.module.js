let LoginModule = (function(){
    //cache DOM
    let $el = $('#loginModule');
    let $loginForm = $('.ui.form');
    let $errorMsg = $el.find('#error-msg');
    //bind events
    $loginForm.on('submit', _authenticate);
    
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
                type   : 'minLength[8]',
                prompt : 'Your password must be at least {ruleValue} characters'
              }
            ]
          },
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

})();

