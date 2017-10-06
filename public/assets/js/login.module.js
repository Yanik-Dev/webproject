let LoginModule = (function(){
    
    //cache DOM
    let $el = $('#loginModule');
    let $button = $el.find('authenticate-button');

    //bind events
    $button.on('click', _authenticate);

    function _authenticate(event){
        $.ajax({
            url: './actions/login.php',
            dataType: 'text',
            type: 'post',
            contentType: 'application/x-www-form-urlencoded',
            data: $(this).serialize(),
            success: _onLogin,
            error: _onError
        });
        $.post('', _onLogin);
        event.preventDefault();
    }

    function _onLogin(data){

    }

    function _onError( jqXhr, textStatus, errorThrown ){
        console.log( errorThrown );
    }

})();