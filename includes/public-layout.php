<?php 

  require '../services/autoload.php';
  //check if user is already logged in
  if( $session !== null){
    if(strtolower($title) =="login" || strtolower($title) =="register"){
      header("Location: ./index.php");
      exit;
    }
  }
  $token = SecurityService::generateToken("token");
  $crsfToken = SecurityService::generateToken("crsf_token");
?>
  <script>
  $(document)
    .ready(function() {
      $('.browse')
      .popup({
        on:'click',
        inline     : true,
        hoverable  : true,
        position   : 'bottom left',
        delay: {
          show: 300,
          hide: 800
        }
      });
     
    });
  </script>
</head>
<body>
<div class="ui pointing main menu">
  <a class="item">

  <img  src="./assets/img/mini-blookup-logo.png" alt="">
  </a>
  <a class="item <?= ($title =='Home')?'active':'' ?>" href="./index.php" >
  <i class="large home icon"></i>
  </a>
  <a class="browse item <?= ($title =='Business')?'active':'' ?>" >
    <i class="large search icon"></i>
    <i class="large building icon"></i>
  </a>
<div class="ui flowing basic popup">

<div class="column">
 <a class="item header" href="./business.php">All <i class="angle right icon"></i></a>  
 </div>
  <div class="ui three column relaxed divided grid">
    <div class="column">
      <h4 class="ui header">Services</h4>
      <div class="ui link list">
        <a class="item">Fashion Design</a>
        <a class="item">Fine Art</a>
        <a class="item">Strategic Design</a>
      </div>
    </div>
    <div class="column">
      <h4 class="ui header">Products</h4>
      <div class="ui link list">
        <a class="item">Anthropology</a>
        <a class="item">Economics</a>
        <a class="item">Media Studies</a>
        <a class="item">Philosophy</a>
      </div>
    </div>
    <div class="column">
      <h4 class="ui header">Social Sciences</h4>
      <div class="ui link list">
        <a class="item">Food Studies</a>
        <a class="item">Journalism</a>
        <a class="item">Non Profit Management</a>
      </div>
    </div>
  </div>
</div>
  <div class="right menu">
  <?php if($session == null): ?>
    <a class="item <?= ($title =='Login')?'active':'' ?>" href="./login.php">
      login
    </a>
    <a class="item <?= ($title =='Register')?'active':'' ?>" href="./register.php">
      Sign Up
    </a>
  <?php else: ?>
    <a class="browse icon item" href="./control-panel/business.php">
      <i class="large list icon"></i> 
    </a>
    <a class="browse icon item" href="../actions/login.php?logout=yes&token=<?=$token?>&page=<?=$title?>">
      <i class="large power icon"></i> 
    </a>
  <?php endif; ?>
  </div>
</div>