<?php 
 $title = "Home";
 include '../includes/header.php';
 include '../includes/public-layout.php';
?>
<div id="search-module">
  <div class="ui container">
    <div class="ui centered grid ">
        <div class="row search-container">
            <button id="filter-btn" class="circular ui icon button">
                    <i class="icon filter"></i>
            </button>
            <!-- search-bar -->
            <div class="ui search" id="main-search">
                <div class="ui icon input">
                        <input class="prompt" placeholder="Search..." type="text">
                        <i class="search icon"></i>
                </div>
                <div class="results"></div>
            </div>
            <!-- ./search-bar -->
        </div>
    </div>
    
    <div class="ui centered grid">
        <?php for($i=0; $i < 10; $i++): ?>
        <div class="sixteen wide mobile eight wide tablet eight wide computer column" >
            <!-- offering-listing -->
            <div class="ui piled segment">
                <div class="ui items">
                    <div class="item">
                            <div class="ui small image">
                            <img src="http://www.placehold.it/300x300">
                            </div>
                            <div class="content">
                                <a class="header">Header</a>
                                <div class="meta">
                                    <p>$ 3000</p>
                                </div>
                                <div class="description">
                                        <p> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quae minima </p>
                                </div>
                                <div class="extra">
                                <div class="ui star rating" data-max-rating="5"></div>
                                <button class="ui right floated icon button">
                                        <i class="phone icon"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ./offering-listing -->
        </div> 
        <?php endfor;?>
    </div>

		
  </div>
    

    
</div>
<script>
let SearchModule = (function(){
    //local variable declaration
    let offerings = [];

    //cache DOM elements
    let $el = $('#search-module');
    let $modal = $el.find('.ui.modal');
    let $typeFilter = $el.find('.ui.type-filter.dropdown');
    let $categoryFilter = $el.find('.ui.category-filter.dropdown');
    let $searchContainer = $el.find('.search-container');
    let $searchBox = $el.find('#main-search');
    let $filterBar = $el.find('.filter-bar');
    let $filterBtn = $el.find('#filter-btn');
    let $ratings =  $el.find('.rating');
    let $loadIndicator = $el.find('.load-indicator');
  
    //events binding
    $filterBtn.on('click', _toggleFilter);

    //init
    //$modal.modal('show');
    $filterBar.hide();
    $ratings.rating();
    $searchContainer.visibility({ type: 'fixed'});
    $loadIndicator.visibility({
        once: false,
        observeChanges: true, 
        onTopVisible: function(calculations) {
            console.log("send request")
            //fetch items from api
        } 
    });

    //local functions
    function _getOfferings(){
        $.ajax({
            url: '../actions/load-offerings.php',
            dataType: 'text',
            type: 'get',
            contentType: 'application/json',
            success: _onSuccess,
            error: _onError
        });
    };

    function _onSuccess(data){

    }

    function _toggleFilter(){
        $filterBar.toggle();
    };

    function _onError( jqXhr, textStatus, errorThrown ){
        console.log( errorThrown );
    }

});

$(function(){
    SearchModule();
})
</script>