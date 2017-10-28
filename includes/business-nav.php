<?php 

  require '../common/autoload.php';
  require '../services/autoload.php';
  require '../models/autoload.php';
  //check if user is already logged in
  $session = SessionService::getActiveSession("user");
  if( $session == null){
      header("Location: ./login.php");
      exit;
  }
  $token = SecurityService::generateToken("token");
  
?>
</head>

  <div class="ui left inline vertical sidebar menu uncover">
        <div class="item sidebar-header" style="">
            <img class="ui  tiny circular image" src="<?=$session->getImage()??'./assets/img/300x300.png'?>">
            <span class="header"><?= strtoupper($session->getFirstname()).' '.strtoupper($session->getLastname())?></span>
        </div>
       <div class="item">
            <div class="header">Offerings</div>
            <div class="menu">
            <a class="item">Products</a>
            <a class="item">Services</a>
            </div>
        </div>
        <div class="item">
            <div class="header">Businesses</div>
            <div class="menu" id="business-list">
            </div>
        </div>
        <div class="item">
            <div class="header">Settings</div>
            <div class="menu">
            <a class="item">Account</a>
            </div>
        </div>
        <div class="item">
            <div class="header">Support</div>
            <div class="menu">
            <a class="item">E-mail Support</a>
            <a class="item">FAQs</a>
            </div>
        </div>
  </div>
  <div class="pusher" style="background-color:#eee">
    <div class="ui top attached main menu">
        <a class="browse icon item" id="menu-btn">
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
        <a class="browse icon item" href="../actions/login.php?logout=yes&token=<?=$token?>&page=<?=$title?>">
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
        console.log(data)
    });
    //local functions
    function _getBusinessList(){
        $.ajax({
            url: './actions/business.php?page=0',
            type: 'get',
            dataType: 'json',
            success:function(data){
                $.each(data,(i,business)=>{
                    $businessList.append('<a class="item" href="">'+business.name+'</a>');
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
