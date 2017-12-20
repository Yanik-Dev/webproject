<?php 
 $title = "Home";
 include '../includes/header.php';
 include '../includes/public-layout.php';
?>
<div id="search-module">
  <div class="ui container">
    <div class="ui centered grid ">
        <div class="row search-container">
           <!-- <button id="filter-btn" class="circular ui icon button">
                    <i class="icon filter"></i>
            </button> -->
            <!-- search-bar -->
            <div class="ui search" id="main-search">
                <div class="ui icon input">
                        <input id = "search-bar" class="prompt" placeholder="Search..." type="text">
                        <i class="search icon"></i>
                </div>
                <div class="results"></div>
            </div>
            <!-- ./search-bar -->
        </div>
    </div>

    <div class=" ui padded centered four stackable cards" id="offerings">
            

    </div>


		
  </div>
    

<script type="text/template" id="offering-template">
<div class="ui card main-card-content" id="offering-item-{{id}}">
  <div class="ui slide masked reveal image">

  <img class="ui image visible content"  src="{{image}}">
    <div class="hidden content" style="padding:5px;" >
        <p id="description">{{description}}</p>
    </div>
  </div>
  <div class="content">

    <h3 id="name" data-id="{{id}}" data-index="{{index}}">{{name}}</h3>
    <b>Cost: <i class="dollar icon"></i> <span id="cost">{{cost}}</span></b>
    <div class="meta" id="category" data-categoryId="{{categoryId}}" data-typeId="{{typeId}}">{{category}}</div>
    <div class="meta" id="business" data-businessId ="{{businessId}}">{{businessName}}</div>
  </div>
  <div class="ui four bottom attached buttons">
  </div>
</div>
</script>


    
</div>

<script src="./assets/js/offering.module.js"></script>