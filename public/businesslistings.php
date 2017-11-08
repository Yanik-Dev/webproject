<?php 
 $title = "Business-List";
 include '../includes/header.php';
 include '../includes/public-nav.php';
?>
   <div class="ui container">
       <div class="ui centered padded grid " id="businesses">

        </div>
    </div>
</div>
<!-- close grid -->

<!-- description modal -->
<div class="ui tiny description modal">
  <div class="header"></div>
  <div class="scrolling content">
    <div class="meta">
        <span><h3><i class="info icon"></i> Description</h3></span>
    </div>
    <p class="description"></p>

    <div class="meta">
        <span><h3><i class="phone icon"></i> Contact Information</h3></span>
    </div>

    <img src="" alt="" class="contact-qrcode" align="left">
    </br />
    <span class="mobile"></span>
    <span class="telephone"></span>
    <span class="email"></span> 
    <span class="website"></span>
  
  </div>
</div>
<!-- ./description modal -->

<!-- business item template -->
<script type="text/template" id="business-template">
<div class="sixteen wide mobile eight wide tablet eight wide computer column segment-cell business-item-{{id}}" >
    <div class="ui raised segment">
        <a class="ui business-name" style="color: #3d3d3d; font-size:16px; font-weight:bold"><span class="name">{{name}}</span></a>
        <div class="ui items">
            <div class="item">
                <div class="ui small image">
                    <img src="{{logo}}">
                </div>
                <div class="content">
                  <div class="meta">
                      <span>Description</span>
                  </div>
                  <div class="description">
                      <span class="description">{{limitText}}</span> <br />
                      <span class="street">{{street}}</span>,  <span class="city">{{city}}</span>,  <span class="province">{{province}}</span><br />
                      <div style="display:none">
                      <span class="mobile">{{mobile}}</span>
                      <span class="telephone">{{telephone}}</span><br />
                      <span class="email">{{email}}</span> <br />
                      <span class="website">{{website}}</span>
                      <span class="qrcode">{{contactQrCode}}</span>
                      </div>
                  </div> 
                  <div class="extra">
                    <button class="mini ui icon labeled  button circular viewMore"  data-id="{{id}}">
                      <i class="large list icon"></i> Offerings
                    </button>
                    <button class="mini ui icon labeled  button circular viewMore"  data-id="{{id}}">
                        <i class="large close icon"></i> More
                      </button>
                  </div>
                </div>
            </div>
        </div>
    </div> 
</div> 
</script>
<!-- ./business item template -->
<script src="./assets/js/app.module.js"></script>
<script src="./assets/js/business.module.js"></script>


