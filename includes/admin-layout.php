<?php 
include '../../includes/panel-header.php';
  
  $token = SecurityService::generateToken("token");
  $image = null;
  if(trim($session->getImage()) != ''){
    $image = $session->getImage();
  }
?>
</head>

  <div class="ui left inline vertical sidebar menu uncover">
        <div class="item sidebar-header" style="">
            <img class="ui  tiny circular image" src="<?='./../uploads/'.($image??'./../assets/img/300x300.png')?>">
            <span class="header"><?= strtoupper($session->getFirstname()).' '.strtoupper($session->getLastname())?></span>
        </div>
       <div class="item">
            <a class="header" href="./offerings.php">Offerings</a>
            <div class="menu">
           <!-- <a class="item">Products</a>
            <a class="item">Services</a> -->
            </div>
        </div>
         <div class="item">
            <a class="header" href="./business.php">Businesses (<span id="no-of-business"></span>)</a>
            <div class="menu" id="business-list">
            </div>
        </div>
        <div class="item">
            <div class="header">Settings</div>
            <div class="menu">
            <a class="item" href="./account.php">Account</a>
            </div>
        </div>
        <div class="item">
            <div class="header">Support</div>
            <div class="menu">
            <a class="item">Coming Soon</a>
           <!-- <a class="item">E-mail Support</a>
            <a class="item">FAQs</a>-->
            </div>
        </div>
  </div>
  <div class="pusher" style="background-color:#eee">
    <div class="ui top attached main menu">
        <a class="ui browse teal icon item" id="menu-btn">
            <i class="larger sidebar icon"></i>
        </a>
    <div class="right menu">
    <div class="ui category search item">
        <div class="ui transparent icon input">
        <input class="prompt" type="text" id="search-bar" placeholder="Search...">
        <i class="search link icon"></i>
        </div>
        <div class="results"></div>
    </div>
        <a class="browse icon item" href="./../actions/login.php?logout=yes&token=<?=$token?>&page=<?=$title?>">
        <i class="large power icon"></i> 
        </a>
    </div>
    </div>
    


    <script>
$(document).ready(function(){
    let $menuBtn = $('#menu-btn');
    let $sideBar = $('.ui.sidebar')
                  .sidebar('attach events', '#menu-btn');

    
  
});

(function($){
    //local variables
    $businessList = $('#business-list');
    

    _getBusinessList();
    
    $(document).bind('results', function(e, data){
        _getBusinessList();
    });
    
    //local functions
    function _getBusinessList(){
        $businessList.html('');
        $.ajax({
            url: './../actions/business.php?admin=yes&page=0',
            type: 'get',
            dataType: 'json',
            success:function(data){
                $('span#no-of-business').text('0');
                $.each(data,(i,business)=>{
                    $businessList.append('<div class="item">'+business.name+'</div>');
                    $('span#no-of-business').text(data.length);
                    
                });
            },
            error: _onError
        })
        
    }

    function _onError( jqXhr, textStatus, errorThrown ){
        console.log( errorThrown );
    }


})(jQuery);
</script>
