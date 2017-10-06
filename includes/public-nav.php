<style type="text/css">
    body {
      background-color: #eee;
    }
    body > .grid {
      height: 100%;
    }
    .column {
      max-width: 450px;
    }
  </style>
  <script>
  $(document)
    .ready(function() {
      $('.ui.form')
        .form({
          fields: {
            email: {
              identifier  : 'email',
              rules: [
                {
                  type   : 'empty',
                  prompt : 'Please enter your e-mail'
                },
                {
                  type   : 'email',
                  prompt : 'Please enter a valid e-mail'
                }
              ]
            },
            password: {
              identifier  : 'password',
              rules: [
                {
                  type   : 'empty',
                  prompt : 'Please enter your password'
                },
                {
                  type   : 'length[6]',
                  prompt : 'Your password must be at least 6 characters'
                }
              ]
            }
          }
        })
      ;
      $('.browse')
      .popup({
        inline     : true,
        hoverable  : true,
        position   : 'bottom left',
        delay: {
          show: 300,
          hide: 800
        }
      });
      $('.rating')
  .rating({
    initialRating: 2,
    maxRating: 4
  })
;
    })


  ;
  </script>
</head>
<body>
<div class="ui pointing menu">
  <a class="item <?= ($title =='Home')?'active':'' ?>" href="./index.php" >
  <i class="large home icon"></i>
  </a>
  <a class="browse item <?= ($title =='Business')?'active':'' ?>" href="./business.php">
    Find Business
  </a>
<div class="ui flowing basic admission popup">
  <div class="ui three column relaxed divided grid">
    <div class="column">
      <h4 class="ui header">Business</h4>
      <div class="ui link list">
        <a class="item">Design &amp; Urban Ecologies</a>
        <a class="item">Fashion Design</a>
        <a class="item">Fine Art</a>
        <a class="item">Strategic Design</a>
      </div>
    </div>
    <div class="column">
      <h4 class="ui header">Liberal Arts</h4>
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
    <a class="item <?= ($title =='Login')?'active':'' ?>" href="./login.php">
      login
    </a>
    <a class="item <?= ($title =='Register')?'active':'' ?>" href="./register.php">
      Sign Up
    </a>
  </div>
</div>