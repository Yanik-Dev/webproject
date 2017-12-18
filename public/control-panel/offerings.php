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
                    <a class="item" id="import-btn"><i class="file excel outline icon"></i> Import Data</a>
                    <a class="item" download="expenses.pdf"><i class="download icon"></i> Download Excel Template</a>
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
  <div class="ui three bottom attached buttons">
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
  </div>
</div>
</script>

<script type="text/template" id="image-template">
     <div class="small centered image" style="position:relative">
        <button class="mini ui icon button circular"  style=" z-index:99;position: absolute; top:5px;right: 0px" data-id="{{id}}">
            <i class="large close icon"></i> 
        </button>
        <img class="ui large image" src="{{image}}">
    </div>
</script>
<!-- ./offering item template -->
<script src="./../assets/js/app.module.js"></script>
<script src="./../assets/lib/jquery.jscroll.js"></script>

<script>
(function(){

    //local variables
    let pagination = {
        page: 1,
        limit: 10,
        total: 0,
        isEnd: false
    }
    
    let previousName = null;
    let isNameUnique = false;
    let offerings = [];


    //cache DOM elements
    let $el = $('#offering-module');
    let $offeringContianer = $('#offerings');
    let $nameInput = $('#name');
    let $offeringForm = $('.ui.form.offering');
    let $searchBar = $('#search-bar');
    let $offeringSubmitButton = $('#offering-submit-btn');
    let $addOfferingModal = $('.offering.modal');
    let $addOfferingButton = $('#add-offering');
    let offeringTemplate = $('#offering-template').html();
    let imageTemplate = $('#image-template').html();
    let $imageView = $('.segment.images');
    let $existErrorMsg = $('#exist-msg');
    let $closeModal = $('.close-modal-btn');
    let $importModal = $('.import.modal');
    let $importForm = $('.import.form');
    let $checkingNameLoader = $el.find(".mini.text.inline.loader");
    let $typeDropdown = $offeringForm.find('#typeId');
    let $businessDropdown = $offeringForm.find('#businessId');
    let $selectedCardContent = null;
    let $loadIndicator = $el.find('.load-indicator');
    let selectedId = null;

    //bind events
    $addOfferingButton.on('click', ()=>{ $addOfferingModal.modal('show'); });
    $('#import-btn').on('click', ()=>{
      $importModal.modal('show');
    })
    $offeringContianer.delegate('button.uploads','click', function(){
        selectedId = $(this).closest('.main-card-content').find('#name').attr("data-id");
        $imageView.toggle();
        $imageView.find('#image-area').empty();
        let index = $(this).closest('.main-card-content').find('#name').attr("data-index");
        $.each(offerings[index].images,(i,image)=>{
            let pic = {image:''};
            pic.image = (image)?'./../uploads/'+image:'./../assets/img/img-wireframe.png';
            $imageView.find('#image-area').append(Mustache.render(imageTemplate, pic));
        });
    })

    $('button.close-image-view').on('click', function(){
        $imageView.toggle();    
        $imageView.find('#image-area').empty();       
    })

    //handles upload events
    $('.upload-input').on('change', function(){
        console.log('initiating upload')
        _onUpload()
    })
    $('#upload-btn').on('click', function(){
        $('.upload-input').trigger('click')
    })
    $nameInput.on('keyup', _checkIfNameExist);
    $businessDropdown.on('change', _checkIfNameExist);
    $offeringContianer.delegate('button.edit','click', _onEdit)
    $addOfferingModal.modal({onHidden: _resetForm});
    $searchBar.on('keyup', $.debounce(_getOfferingList, 300));
    $closeModal.on('click', ()=>{ _resetForm() });
    $typeDropdown.on('change', _getCategories);
    $offeringSubmitButton.on('click', _submitOffering);
    $('.description-textarea').keyup(function() {
      var textLength = $(this).val().length;
      var textRemaining = 255 - textLength;
      $('#textarea-feedback span.remaining').html(textRemaining);
    });

    $(window).scroll(function() {
      if ($(window).scrollTop() == $(document).height() - $(window).height() && !pagination.isEnd) {
          _getOfferingList();
      }
    });

    function _onUpload(){
        var filedata = $('.upload-input')[0],
        formdata = false;
        if (window.FormData) {
            formdata = new FormData();
        }
        var i = 0, len = filedata.files.length, img, reader, file;
        for (; i < len; i++) {
            file = filedata.files[i];
            if (window.FileReader) {
                reader = new FileReader();
                reader.onload = function(e) {
                // showUploadedItem(e.target.result, file.fileName);
                };
                reader.readAsDataURL(file);
            }
            formdata.append("file", file);
        }
        var formData = new FormData($('#upload-form')[0]);
        if (formdata) {
            $.ajax({
                url: "../../actions/offering.php?id="+selectedId,
                type: "POST",
                data: formData,
                cache: false,
                processData: false,
                contentType: false,
                success: function(res) {
                    console.log(res)
                },       
                error: function(res) {

                }       
            });
        }
    }


    //init
    $('.ui.options.dropdown').dropdown();
    $importForm.form({
        on: 'blur',
        inline: true,
        fields: {
            businessId: {
              identifier: 'businessId',
              rules: [
                {
                  type   : 'empty',
                  prompt : 'Please select a business'
                }
              ]
            },
            file: {
              identifier: 'file',
              rules: [
                {
                  type   : 'empty',
                  prompt : 'Please select a file'
                }
              ]
            },
        }
    });
    $offeringForm.form({
        on: 'blur',
        inline: true,
        fields: {
            name: {
              identifier: 'name',
              rules: [
                {
                  type   : 'empty',
                  prompt : 'Name cannot be empty'
                }
              ]
            },
            typeId: {
              identifier: 'typeId',
              rules: [
                {
                  type   : 'empty',
                  prompt : 'Please select a type'
                }
              ]
            },
            businessId: {
              identifier: 'businessId',
              rules: [
                {
                  type   : 'empty',
                  prompt : 'Please select a offering'
                }
              ]
            },
            categoryId: {
              identifier: 'categoryId',
              rules: [
                {
                  type   : 'empty',
                  prompt : 'Please select a category'
                }
              ]
            },
            cost: {
              identifier: 'cost',
              rules: [

                {
                  type   : 'empty',
                  prompt : 'A numeric value is expected'
                },
                {
                  type   : 'number',
                  prompt : 'A numeric value is expected'
                }
              ]
            },
            description: {
              identifier: 'description',
              rules: [
                {
                  type   : 'maxLength[255]',
                  prompt : 'Description have a max of 255 characters'
                }
              ]
            },
        }
        
    });

    _getOfferingList();

    //local functions
    function _getOfferingList(){
        let q = (this.value)?this.value.trim():'';
        let $images = [];
        if(q){
            pagination.page = 0;
        }
        $.ajax({
            url: './../actions/offering.php?search='+q+'&page='+pagination.page+'&limit='+pagination.limit,
            type: 'get',
            dataType: 'json',
            success:function(data){
              
                //$offeringContianer.empty();
                if(data.length == 0 && q ==""){
                    $addOfferingButton.find("span").text("Add Your First Offering");
                    return;
                }
                if(data[0].endOfResults === true){
                    $loadIndicator.hide();
                    pagination.isEnd = true;
                    return;
                }
                $.each(data,(i,offering)=>{
                    offering.image = (offering.image)?'./../uploads/'+offering.image:'./../assets/img/img-wireframe.png';
                    offering.index = i;
                    $offeringContianer.append(Mustache.render(offeringTemplate, offering));
                    let $item = $offeringContianer.find('div.offering-item-'+offering.id);
                    $item.find('.ui.uploading.dimmer').addClass('active');
                    $images[i] = $item.find('img');
                    $images[i].on("load", ()=>{
                       // $images[i].closest('.segment').find('.ui.uploading.dimmer').removeClass('active');
                    })

                    offerings.push(offering);
                });
                pagination.page++;
            },
            error: _onError
        })
        
    }

    function _getCategories(){
        $.ajax({
          url: './../actions/offering.php?type='+$typeDropdown.val(),
          dataType: 'text',
          type: 'get',
          contentType: 'application/x-www-form-urlencoded',
          success: function(data){
            let result = JSON.parse(data);
            $categoryDropdown = $offeringForm.find('#categoryId');
            $categoryDropdown.find('option').remove('#category-option');
            if(result.length > 0){
                $categoryDropdown.removeAttr('disabled');
                $.each(result, (key, el)=>{
                    $offeringForm.find('#categoryId').append('<option id="category-option" value="'+el.id+'">'+el.category+'</option>');
                })
                //check if called in edit mode
                //select category 
                if($selectedCardContent){
                    let categoryId = $selectedCardContent.find('div#category').attr('data-categoryId');
                    $offeringForm.find('select#categoryId').val(categoryId);
                    //$selectedCardContent = null;
                }
                
            }else{
                $('#categoryId').attr('disabled', true);
            }
          },
        });
    }


    function _onEdit(){
        $selectedCardContent = $(this).closest('.main-card-content');
        let name = $selectedCardContent.find('#name').text();
        let description = $selectedCardContent.find('p#description').text();
        let cost = $selectedCardContent.find('span#cost').text();
        let businessId = $selectedCardContent.find('div#business').attr('data-businessId');
        let typeId = $selectedCardContent.find('div#category').attr('data-typeId');
        let id = $selectedCardContent.find('#name').attr('data-id');
        $offeringForm.find('input#name').val(name);
        $offeringForm.find('textarea#description').val(description);
        $offeringForm.find('input#cost').val(cost);
        $offeringForm[0].id.value = id;
        $offeringForm.find('select#businessId').val(businessId);
        $offeringForm.find('select#typeId').val(typeId);
        $typeDropdown.trigger('change');
        previousName = name;
        $addOfferingModal.modal('show');
    }

    function _onEditFinish(){
        let name =  $offeringForm.find('input#name').val();
        let description = $offeringForm.find('textarea#description').val();
        let cost = $offeringForm.find('input#cost').val(); 
        let businessId = $offeringForm.find('select#businessId').val(); 
        let category = $offeringForm.find('select#categoryId option:selected').text();
        let categoryId = $offeringForm.find('select#categoryId').val();
        $selectedCardContent.find('#name').text(name)
        $selectedCardContent.find('p#description').text(description);
        $selectedCardContent.find('span#cost').text(cost);
        $selectedCardContent.find('div#business').attr('data-businessId', businessId);
        $selectedCardContent.find('div#category').attr('data-typeId', categoryId);
        $selectedCardContent.find('div#category').text(category);
        _resetForm();
    }

    function _resetForm(){
        $offeringForm[0].reset();   
        $offeringForm.find('.error').removeClass('error');
        $offeringForm.find('.ui.basic.red.pointing.prompt.label').remove();
        $('#textarea-feedback span.remaining').html(255);
        $existErrorMsg.hide();
        $addOfferingModal.modal('hide'); 
    }

    function _submitOffering(){

        if(previousName == $offeringForm.find('input#name').val()){
            isNameUnique = true;
        }
        if( !$offeringForm.form('is valid')  ||  !isNameUnique){
            $offeringForm.form('validate form')
            return;
        }
        $.ajax({
            url: './../actions/offering.php',
            dataType: 'text',
            type: 'post',
            contentType: 'application/x-www-form-urlencoded',
            data: $offeringForm.serialize(),
            success: (response)=>{
                console.log(response);
                if($offeringForm[0].id.value > 0){
                    _onEditFinish()
                }
            },
            error: _onError
        })
    }

    function _checkIfNameExist(){
        if( !$offeringForm.form('is valid', "name") ){
          return;
        }
        let businessId = $offeringForm.find('select#businessId').val();

        $existErrorMsg.hide();
        //check name on edit
        if(previousName == $nameInput[0].value && businessId > 0 ){
            isNameUnique = true;
            return;
        }
        
        $checkingNameLoader.addClass("active");
        $.ajax({
          url: './../actions/offering.php?businessId='+businessId+'&name='+$nameInput[0].value,
          dataType: 'text',
          type: 'get',
          contentType: 'application/x-www-form-urlencoded',
          success: function(data){
            $checkingNameLoader.removeClass("active");
            let result = JSON.parse(data);
            if(result){
              $existErrorMsg.show();
              isNameUnique = false;
            }else{
              isNameUnique = true;
            }
          },
          error: _onError
        });
    }


    function _onError( jqXhr, textStatus, errorThrown ){
        console.log( errorThrown );
        $errorMsg.text("An unexpected error as occured.");
        $errorMsg.show();
    }


})();
    
        
       

</script>
