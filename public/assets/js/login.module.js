let LoginModule = (function(){
    console.log("init")
    //cache DOM
    let $el = $('#loginModule');
    let $loginForm = $('#login-form');

    //bind events
    $loginForm.on('submit', _authenticate);

    function _authenticate(event){
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
    }

    function _onError( jqXhr, textStatus, errorThrown ){
        console.log( errorThrown );
    }

})();