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
    let $confirmModal = $('.ui.basic.confirm.modal');
    let selectedId = null;
    let previousSearch = '';

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

    $offeringContianer.delegate('button.delete','click', function(){
        let $item = $(this).closest('.main-card-content');
        selectedId = $item.find('#name').attr("data-id");
        let index = $(this).closest('.main-card-content').find('#name').attr("data-index");
        $confirmModal.modal({
            closable : false,
            onDeny    : function(){
                $confirmModal.modal('hide');
              return false;
            },
            onApprove : function() {
                $.ajax({
                    url: './../actions/offering.php?delete='+selectedId,
                    type: 'get',
                    dataType: 'text',
                    success:function(data){
                        console.log(data, selectedId)
                        let result = JSON.parse(data);
                        if(result){
                            $item.remove();
                        }else{
                            
                        }
                    },
                    error: _onError
                })
            }
          })
          .modal('show');
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
    $searchBar.on('keyup', $.debounce(_search, 300));
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
            let ext = file.name.substring(file.name.lastIndexOf('.') + 1).toLowerCase();
            if (file.size > 2000000 || file.fileSize > 2000000){
                
                continue;
            }
            if (ext != "png" || ext != "jpg" || ext != "jpeg") {

               continue; 
            }
            
            if (window.FileReader) {
                reader = new FileReader();
                reader.onload = function(e) {
                    let pic = {image:''};
                    pic.image = file.fileName;
                    console.log(e.target.result)
                    $imageView.find('#image-area').append(Mustache.render(imageTemplate, pic));
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
    function _search(){
        $offeringContianer.empty();
        _getOfferingList();
    }
    function _getOfferingList(){
        let q = ($searchBar.val())?$searchBar.val().trim():'';
        
        if(q != '' && previousSearch != q){
            pagination.page = 0;
        }
        else if(q != '' && previousSearch == q){
            $loadIndicator.hide();
            pagination.isEnd = true;
            return;
        }
        previousSearch = q;
        let $images = [];
        $.ajax({
            url: './../actions/offering.php?search='+q+'&page='+pagination.page+'&limit='+pagination.limit,
            type: 'get',
            dataType: 'json',
            success:function(data){
                console.log(data, data.length)
            
                if(data.length == 0 && q ==""){
                    $loadIndicator.hide();
                    pagination.isEnd = true;
                    $addOfferingButton.find("span").text("Add Your First Offering");
                    return;
                }
                else if(data[0].endOfResults){
                    $loadIndicator.hide();
                    pagination.isEnd = true;
                    return;
                }
                else{
                    $loadIndicator.show();
                    pagination.isEnd = false;
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
    }


})();
    
        