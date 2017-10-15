let LoginModule = (function(){
    console.log("init")
    //cache DOM
    let $el = $('#loginModule');
    let $button = $el.find('#authenticate-button');
    let $loginForm = $('#login-form');

    //bind events
    $button.on('click', _authenticate);

    function _authenticate(){
        console.log($loginForm)
        $.ajax({
            url: '../actions/login.php',
            dataType: 'text',
            type: 'post',
            contentType: 'application/x-www-form-urlencoded',
            data: $loginForm.serialize(),
            success: _onLogin,
            error: _onError
        });
        $loginForm.preventDefault();
    }

    function _onLogin(data){
        console.log(data)
    }

    function _onError( jqXhr, textStatus, errorThrown ){
        console.log( errorThrown );
    }

})();