
let appModule = (function(){
    //cache DOM elements
    let appLevelAlert = $('.app-level.nag');

    //init
    checkifUserIsOffline()
    
    //bind events

    /**
     * setup event listeners to check device
     * is online or offline
     */
    window.addEventListener("offline", function(event){
        checkifUserIsOffline()
    });
    window.addEventListener("online", function(event){
        checkifUserIsOffline()
    });


    //event handlers
    /**
     * check if user is online
     */
    function checkifUserIsOffline(){
        if(navigator.onLine){
            _sendUselessRequest((response)=>{
                if(response == 'online'){
                    appLevelAlert.nag('hide');
                }else{
                    appLevelAlert.nag('show');
                }
            })
            
        }else{
            appLevelAlert.nag('show');  
        }
    }

    /**
     * send a request to an external server to test
     * internet connectivity
     * @param {} response 
     */
    function _sendUselessRequest(response){
        $.ajax({
            url:'//webproject.com/actions/business.php?page=0',
            dataType: 'text',
            type: 'get',
            success:()=>{
                response('online');
            },
            error: ()=>{
                response('offline');
            }   
        })
    }
    
})();