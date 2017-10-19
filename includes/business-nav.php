<?php 

  require '../services/autoload.php';
  //check if user is already logged in
  $session = SessionService::getActiveSession("user");
  if( $session !== null){
      //header("Location: ./index.php");
      //exit;
  }
  $token = SecurityService::generateToken("token");
  
?>
</head>
<script>
$(document).ready(function(){
    let $menuBtn = $('#menu-btn');
    let $sideBar = $('#sidebar');
    $menuBtn.on("click", function(){
        $sideBar.toggle();
    })
});
</script>
<body>
<div class="ui top menu">
    <a class="browse icon item" id="menu-btn">
        <i class="larger sidebar icon"></i>
    </a>
  <div class="right menu">
  <div class="ui category search item">
    <div class="ui transparent icon input">
      <input class="prompt" type="text" placeholder="Search animals...">
      <i class="search link icon"></i>
    </div>
    <div class="results"></div>
  </div>
    <a class="browse icon item" href="../actions/login.php?logout=yes&token=<?=$token?>&page=<?=$title?>">
      <i class="large power icon"></i> 
    </a>
  </div>
</div>

<div class="ui grid">
    <div class="ui secondary vertical pointing menu" id="sidebar">
    <div class="item">
        <div class="header">Offerings</div>
        <div class="menu">
        <a class="item">Products</a>
        <a class="item">Services</a>
        </div>
    </div>
    <div class="item">
        <div class="header">Businesses</div>
        <div class="menu">
        <a class="item">Rails</a>
        <a class="item">Python</a>
        <a class="item">PHP</a>
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

