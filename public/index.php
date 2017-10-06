<?php 
 $title = "Home";
 include '../includes/header.php';
 include '../includes/public-nav.php';
?>

<script>

</script>
<div class="ui container">

  <div class="ui centered grid">
    <div class="row">
        <!-- search-bar -->
        <div class="ui search">
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
        <div class="sixteen wide mobile eight wide tablet six wide computer column" >
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
<div class="ui modal">
<i class="close icon"></i>
<div class="image content">
  <div class="image">
    <img src="http://www.placehold.it/300x300">
  </div>
  <div class="description">
    A description can appear on the right
  </div>
</div>
<div class="actions">
  <div class="ui button">Cancel</div>
  <div class="ui button">OK</div>
</div>
</div>
<script>
$(function(){

    $('.ui.modal')
  .modal('show')
;
});
</script>