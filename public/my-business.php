<?php 
 $title = "Business-List";
 include '../includes/header.php';
 include '../includes/business-nav.php';
?>

    <div class="ui container">

    <div class="ui centered padded grid">
        <div class="sixteen column" >
            <button class="fluid ui icon button" id="add-business">
                    <i class="plus icon"></i> Add Your First Business
            </button>
        </div>
    </div>

    <div class="ui centered padded grid">
        <?php for($i=0; $i < 4; $i++): ?>
        <div class="sixteen wide mobile eight wide tablet eight wide computer column" >
            <div class="ui raised segment">
            <a class="ui ribbon label" style="font-size:14px"><i class="building icon"></i>My Name is Yanik Blake And I attend VTDI</a>
                <div class="ui items">
                    <div class="item">
                        <div class="ui tiny image">
                        <img src="./assets/img/300x300.png">
                        </div>
                        <div class="content">
                        
                        <div class="meta">
                            <span>Description</span>
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptatibus incidunt neque cupiditate fuga doloribus soluta ipsum nisi repellat tenetur! Sequi aliquid esse corporis quae nam qui omnis culpa mollitia distinctio!
                        </div>
                        <div class="description">
                            <p></p>
                        </div> 
                        <div class="extra">
                          <button class="mini ui button">Edit</button>
                          <button class="mini ui button">View Offerings</button>
                          <button class="mini ui button">Delete</button>
                           
                        </div>
                        </div>
                    </div>
                </div>
            </div> </div> 
        <?php endfor;?>
        </div>
    </div>
</div>
<!-- close grid -->
<script>
$(document).ready(function(){
    //cache DOM
    let $addBusinessBtn = $('#add-business');
    let $closeModal = $('#close-modal-btn');
    let $modal = $('.ui.modal');

    //bind events
    $addBusinessBtn.on('click', _toggleModal);
    $closeModal.on('click', ()=>{ $modal.modal('hide'); });

    function _toggleModal(){
        $modal.modal('show');
    }
    
})

</script>
<div class="ui mini modal">
  <div class="header">
    New Business
  </div>
  <div class=" content">
    <form action="" class="ui form">
    <div class="sixteen wide field">
      <label>Business Name</label>
      <input type="text" placeholder="">
    </div>
    <div class="sixteen wide field">
        <label>Business Description</label>
        <textarea rows="2"></textarea>
    </div>
    </form>
  </div>
  <div class="actions">
    <div class="ui button" id="close-modal-btn">Cancel</div>
    <div class="ui button">OK</div>
  </div>
</div>