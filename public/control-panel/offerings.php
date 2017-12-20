<?php
$title = "Offerings";
include '../../includes/admin-layout.php';
 include '../../services/autoload.php';

 $types = OfferingTypeService::findAll();
 $businesses = BusinessService::findAll($session->getUserId());
?>

<div class="ui segment images hide-element"style="position:fixed;left: 5px; z-index:999999;display:none !important;width: 100%; height: 100% !important;">
        <div>
        <button class="ui primary labeled icon button" id="upload-btn">
            <span>Upload Images</span> 
            <i class="upload icon"></i>
        </button>
        <form enctype="multipart/form-data" style="display:none" action="upload.php" id="upload-form" method="post">
         <input class="upload-input"  type="file" name="file[]" multiple >
        </form>
        <button class="ui red right floated labeled icon button close-image-view">
            <span>Close</span> 
            <i class="close icon"></i>
        </button>
        </div>
        <div class="ui horizontal divider">Preview </div>
        <div class="ui small images" style="overflow-y:scroll" id="image-area">
         
        </div>
        
    </div> 
  
   <div class="ui container" id="offering-module">
    
        <div class="ui centered padded grid">
            <div class="eight column" >
              <div class="ui primary buttons">
              <button class="ui  labeled icon button" id="add-offering">
                <span>Add New Offering</span> 
                <i class="add icon"></i>
              </button>
                <div class="ui options floating dropdown icon button">
                    <i class="dropdown icon"></i>
                    <div class="menu">
                    <a class="item"  id="import-btn" ><i class="file excel outline icon"></i> Import Data</a>
                    <a class="item" href="../assets/blookup_template.xlsx" download><i class="download icon"></i> Download Excel Template</a>
                    </div>
                </div>
                </div>
            </div>
        </div>
        <h6 class="ui horizontal divider header"></h6>
        <div class=" ui padded centered four stackable cards" id="offerings">
            

        </div>

        <div class="load-indicator centered ui grid" >
            <div class="ui  icon message column" style="background-color:transparent; box-shadow:0 0 0 0 rgba(0,0,0,0) inset, 0 0 0 0 transparent !important;">
                <i class="notched circle loading icon"></i>
                <div class="content">
                    <div class="header"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- close grid -->

<!-- offering form modal -->
<div class="ui medium offering modal">
  <div class=" content">
    <button class="mini ui icon button circular close-modal-btn"  style="background-color:transparent; position: absolute; top:3px;right: 3px;" data-id="{{id}}">
        <i class="large close icon"></i> 
    </button>
    <div class="ui negative message hide-element" id="error-msg"></div>
        <form action="" class="ui form offering">
        <h4 class="ui dividing header"><i class="building icon"></i> Offering Information</h4>
        <div class="sixteen wide field">
        <label>Offering Name</label>
             <input type="text" name="name" id="name" placeholder="">
             <span class="ui hide-element" style="color: red" id="exist-msg"><i class="close icon"></i>Offering already exist.</span>
        </div>
        <div class="eight wide field">
           <label>Cost</label>
           <div class="ui right labeled input">
            <label for="amount" class="ui label">$</label>
            <input type="text" name="cost" id="cost" placeholder="Amount" id="amount">
            <div class="ui basic label">.00</div>
          </div>
        </div>
        <div class="sixteen wide field">
            <label>Offering Description</label>
            <textarea rows="2" maxlength="255" name="description" class="description-textarea" id="description"></textarea>
            <div id="textarea-feedback"><span class="remaining">255</span>/255</div>
        </div>

        <div class="eight wide field">
            <select class="ui dropdown" name="businessId" id="businessId">
            <option value="">Select Business</option>
            <?php foreach($businesses as $offering): ?>
                    <option value="<?=$offering['id']?>"><?=$offering['name']?></option>
            <?php endforeach; ?>
        </select>
        </div>
        <div class="field">
            <div class="fields">
                <div class="eight wide field">
                <select class="ui dropdown" id="typeId" name="typeId" >
                    <option value="">Select Offering Type</option>
                    <?php foreach($types as $type): ?>
                        <option value="<?=$type['id']?>"><?=$type['type']?></option>
                    <?php endforeach; ?>
                </select>
                </div>
                <div class="eight wide field">
                <select class="ui dropdown" id="categoryId" name="categoryId" disabled>
                 <option value="">Select Offering Category</option>
                </select>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="token" value="<?=SecurityService::generateToken("crsf_token");?>">
    <input type="text" class="hide-element" name="id" id="id" value="">
    </form>
    <div class="actions">
    <div class="ui button close-modal-btn" >Cancel</div>
    <button class="ui button" id="offering-submit-btn">OK</button>
  </div>
  </div>
  
</div>
<!-- ./offering form modal -->


<!-- confirmation Modal -->
<div class="ui basic confirm modal">
  <div class="ui icon header">
    <i class="window close outline icon"></i>
    Are you sure you want to delete this Item?
  </div>
  <div class="content">
    <p></p>
  </div>
  <div class="actions">
    <div class="ui red  inverted button deny">
      <i class="remove icon"></i>
      No
    </div>
    <div class="ui green basic inverted button approve">
      <i class="checkmark icon"></i>
      Yes
    </div>
  </div>
</div>
<!-- ./confirmation modal -->


<!-- import excel data modal -->
<div class="ui mini import modal">
<div class="header">Import Data</div>
<div class="content">
    <form action="./import.php" class="ui import form" enctype="multipart/form-data" method="POST">
        <div class="sixteen wide field">
            <select class="ui dropdown" name="businessId" id="businessId">
                <option value="">Select Business</option>
                <?php foreach($businesses as $offering): ?>
                        <option value="<?=$offering['id']?>"><?=$offering['name']?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="sixteen wide field">
            <input type="file" name="file" id="file">
        </div>
   
         <input type="hidden" name="token" value="<?=$token?>">

        <div class="actions">
            <button type="submit" class="ui approve button">Submit</button>
            <button type="button" class="ui cancel button">Cancel</button>
        </div>
    </form>
</div>
</div>
<!-- ./import excel data modal -->

<!-- offering template -->
<style>
    .ui.slide.reveal{
        white-space: normal !important;
    }
</style>

<script type="text/template" id="offering-template">
<div class="ui card main-card-content" id="offering-item-{{id}}">
<!--<div class="content">
  </div>-->
  <div class="ui slide masked reveal image">

  <img class="ui image visible content"  src="{{image}}">
    <div class="hidden content" style="padding:5px;" >
        <p id="description">{{description}}</p>
    </div>
  </div>
  <div class="content">

    <!--<span class="right floated">
      <i class="heart outline like icon"></i>
      17 likes
    </span> -->

    <h3 id="name" data-id="{{id}}" data-index="{{index}}">{{name}}</h3>
    <b>Cost: <i class="dollar icon"></i> <span id="cost">{{cost}}</span></b>
    <div class="meta" id="category" data-categoryId="{{categoryId}}" data-typeId="{{typeId}}">{{category}}</div>
    <div class="meta" id="business" data-businessId ="{{businessId}}">{{businessName}}</div>
  </div>
  <div class="ui four bottom attached buttons">
    <button class="ui icon button uploads">
        <i class="photo icon"></i>
    </button>
    <button class="ui primary icon button edit">
        <i class="edit icon"></i>
        Edit
    </button>
    <button class="ui icon primary button">
        <i class="building icon"></i>
    </button>
    <button class="ui icon red delete button">
        <i class="remove icon"></i>
    </button>
  </div>
</div>
</script>

<script type="text/template" id="image-template">
     <div class="small centered image" style="position:relative">
        <button class="mini ui icon button circular"  style=" z-index:99;position: absolute; top:5px;right: 0px" data-id="{{id}}">
            <i class="large close icon"></i> 
        </button>
        <img data-lightbox="image-1" class="ui large image" src="{{image}}">
    </div>
</script>
<!-- ./offering item template -->
<script src="./../assets/js/app.module.js"></script>
<script src="./../assets/js/offering.module.js"></script>

