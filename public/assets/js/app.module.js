
let appModule = (function(){
    //cache DOM elements
    let appLevelAlert = $('.app-level.nag');


    //init
    checkifUserIsOffline()
    
    //bind events
    window.addEventListener("offline", function(event){
        checkifUserIsOffline()
    });
    window.addEventListener("online", function(event){
        checkifUserIsOffline()
    });

    
    function checkifUserIsOffline(){
        if(navigator.onLine)
            appLevelAlert.nag('hide');
        else
            appLevelAlert.nag('show');  
    }
    

})();