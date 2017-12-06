let BusinessModule = (function($){
    //local variables
    let exist = 0;
    let previousName = '';
    let $selectedElement;
    let pagination = {
        page: 1,
        limit: 10
    }

    //cache DOM elements
    let $el = $('#business-module');
    let $businessForm = $('.ui.form');
    let $existErrorMsg = $('#exist-msg');
    let $nameInput = $('#name');
    let $searchBar = $('#search-bar');
    let $businesses = $('#businesses');
    let businessTemplate = $('#business-template').html();
    let $checkingNameLoader = $el.find(".mini.text.inline.loader");
    let $businessFormSubmitBtn = $('#business-submit-btn');
    let $errorMsg = $('#error-msg'); 
    let $addBusinessBtn = $('#add-business');
    let $closeModal = $('.close-modal-btn');
    let $modal = $('.ui.business.modal');
    let $descriptionModal = $('.ui.tiny.description.modal');
    let $confirmModal = $('.ui.basic.confirm.modal');

    let formElements = {
        $id : $businessForm.find('input#id')[0],
        $description:$businessForm.find('textarea#description')[0],
        $name:$businessForm.find('input#name')[0],
        $street: $businessForm.find('input#street')[0],
        $city: $businessForm.find('input#city')[0],
        $province: $businessForm.find('input#province')[0],
        $mobile: $businessForm.find('input#mobile')[0],
        $telephone: $businessForm.find('input#telephone')[0],
        $email: $businessForm.find('input#email')[0],
        $website: $businessForm.find('input#website')[0],
    }
   
    //init
    _getBusinessList()
    $businessForm.form({
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
            description: {
              identifier: 'description',
              optional: true,
              rules: [
                {
                  type   : 'maxLength[255]',
                  prompt : 'Description have a max of 255 characters'
                }
              ]
            },
            mobile: {
                identifier: 'mobile',
                optional: true,
                rules: [
                    {
                        type   : 'regExp',
                        value  : '/^\\s*(?:\\+?(\\d{1,3}))?[-. (]*(\\d{3})[-. )]*(\\d{3})[-. ]*(\\d{4})(?: *x(\\d+))?\\s*$/',
                        prompt : 'Phone number is invalid. Expected Format: (XXX) 000-0000'
                    }
                 ]
            },
            telephone: {
                identifier: 'telephone',
                optional: true,
                rules: [
                    {
                        type   : 'regExp',
                        value  : '/^\\s*(?:\\+?(\\d{1,3}))?[-. (]*(\\d{3})[-. )]*(\\d{3})[-. ]*(\\d{4})(?: *x(\\d+))?\\s*$/',
                        prompt : 'Phone number is invalid. Expected Format: (XXX) 000-0000'
                    }
                 ]
            },
            website: {
                identifier: 'website',
                optional: true,
                rules: [
                    {
                        type   : 'url',
                        prompt : 'Invalid website address. Expected Format: http://example.com'
                    }
                 ]
            },
        }
        
    });
    
    //bind events
    $businessFormSubmitBtn.on('click', _registerBusiness);
    $nameInput.bind('blur', _checkIfNameExist);
    $addBusinessBtn.on('click', _toggleModal);
    $modal.modal({onHidden: _resetBusinessForm});
    $searchBar.on('keyup', $.debounce(_getBusinessList, 300));
    

    $closeModal.on('click', ()=>{ 
        _resetBusinessForm()
    });
    
    $businesses.delegate('.editBtn', 'click', _onEdit)
    $businesses.delegate('.deleteBtn', 'click', _onDelete)
    $businesses.delegate('.viewMore', 'click', _onViewMoreDescription)
    $businesses.delegate('.file-input','change',_onUpload);
    $('.description-textarea').keyup(function() {
        var textLength = $(this).val().length;
        var textRemaining = 255 - textLength;

        $('#textarea-feedback span.remaining').html(textRemaining);
    });

    
    //events handlers  
    function _onUpload(){
        
        $uploadErrorMsg.addClass("hide-element");
        let imgPath = $(this)[0].value;
        let $this = $(this);
        let $container = $this.closest('div.segment-cell')
        let $dimmer = $container.find('.ui.uploading.dimmer');
        let $uploadErrorMsg = $container.find('.mini.bottom.attached.error.message');
        let ext = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
        if ($this[0].files[0].size > 2000000 || $this[0].files[0].fileSize > 2000000){
            $uploadErrorMsg.find('span').text("File cannot exceed 2MB.");
            $uploadErrorMsg.removeClass("hide-element");
            return;
        }
        if (ext == "gif" || ext == "png" || ext == "jpg" || ext == "jpeg") {
            if (typeof (FileReader) == "undefined") {
                $uploadErrorMsg.find('span').text("This browser does not support FileReader.");
                $uploadErrorMsg.removeClass("hide-element");
                return;
            }
        } else {
            $uploadErrorMsg.find('span').text("This browser does not support FileReader."); 
            $uploadErrorMsg.removeClass("hide-element");
            return;     
        }
        
        let imageViewer = $this.closest('.ui.small.image').find('img');
        let file = $this[0].files[0];
        let reader = new FileReader();
        reader.onload = function (e) {
            
            formData = new FormData();
            formData.append('file', file);
            $dimmer.addClass('active');
            $dimmer.find(".text.loader").text('Uploading');
            $.ajax({
                url: '../../actions/business.php?id='+$this.closest('.item').find('.editBtn').attr("data-id"),
                type: 'POST',
                dataType: 'text',
                contentType: false,
                processData: false,
                data: formData,
                success:function(data){
                    console.log(data)
                    let result = JSON.parse(data);
                    if(!result){
                        imageViewer.attr('src', 'assets/img/img-wireframe.png');  
                    }else{
                        imageViewer.attr('src', e.target.result)   
                    }

                    $dimmer.removeClass('active');
                },
                error: _onError,
                xhr: function () {
                    
                    let xhr = $.ajaxSettings.xhr();
                    xhr.upload.onprogress = function (e) {
                        let percent = Math.floor(e.loaded / e.total * 100);  
                        $dimmer.find('.text').text('uploading '+percent+'%')                              
                       
                    };
                    return xhr;
                }
            })
        }
        reader.readAsDataURL($(this)[0].files[0]);
    }


    function _onViewMoreDescription(){
        $item = $(this).closest('div.segment-cell');
        let mobile = (($item.find('span.mobile').html())?'Contact No.1: '+$item.find('span.mobile').html()+' <br />':'');
        let telephone = (($item.find('span.telephone').html())?'Contact No.2: '+$item.find('span.telephone').html()+' <br />':'');
        let email = (($item.find('span.email').html())?'Email: '+$item.find('span.email').html()+' <br />':'');
        let website = (($item.find('span.website').html())?'Website: '+$item.find('span.website').html()+' <br />':'');
        $descriptionModal.find('.header').text($item.find('span.name').html());
        $descriptionModal.find('.description').text($item.find('span.description').html());
        $descriptionModal.find('.mobile').html(mobile);
        $descriptionModal.find('.telephone').html(telephone);
        $descriptionModal.find('.email').html(email);
        $descriptionModal.find('.website').html(website);
        $descriptionModal.find('.contact-qrcode').attr('src', $item.find('span.qrcode').text());
    
        $descriptionModal.modal('show');

    }

    function _resetBusinessForm(){
        $businessForm[0].reset();   
        $businessForm.find('.error').removeClass('error');
        $businessForm.find('.ui.basic.red.pointing.prompt.label').remove();
        
        $('#textarea-feedback span.remaining').html(255);
        $modal.modal('hide'); 
    }

    function _onDelete(){

        $item = $(this).closest('div.segment-cell');
        $id = $(this).attr("data-id")
        $confirmModal.modal({
            closable : false,
            onDeny    : function(){
                $confirmModal.modal('hide');
              return false;
            },
            onApprove : function() {
                $.ajax({
                    url: '../../actions/business.php?delete='+$id,
                    type: 'get',
                    dataType: 'text',
                    success:function(data){
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
    }  

    function _toggleModal(){
      $modal.find('#business-submit-btn').text('Save')
      $modal.modal('show');
    }

    function _registerBusiness(){
        if( !$businessForm.form('is valid')  || exist){
            $errorMsg.hide();
            return;
        }
        $.ajax({
            url: '../../actions/business.php',
            dataType: 'text',
            type: 'post',
            contentType: 'application/x-www-form-urlencoded',
            data: $businessForm.serialize(),
            success: _onSubmit,
            error: _onError
        })
    }

    function _onSubmit(data){
        console.log(data)
        let result = JSON.parse(data);
        if(result.status == 200){
            let business = result.content;
            if(formElements.$id.value > 0){
               $selectedElement.find('span.qrcode').html('./uploads/'+business.contactQrCode);
                _onEditFinish();
            }else{
                 //limit the number of characters shown for the description
                 business.limitText = function(){
                    let ret = this.description;
                    let maxLength = 45;
                    if (ret.length > maxLength) {
                        ret = ret.substr(0,maxLength-3) + "...";
                    }
                    return ret;
                }
                business.logo = (business.logo)?'./uploads/'+business.logo:'assets/img/img-wireframe.png';
                business.contactQrCode = (business.contactQrCode)?'./uploads/'+business.contactQrCode:'';
                $businesses.append(Mustache.render(businessTemplate, business));
            }
            $errorMsg.hide();  
            _resetBusinessForm();      
        }else{
            $errorMsg.text("Server error");
            $errorMsg.show();  
        }
    }

    function _onEditFinish(){
        let descriptionLimit = formElements.$description.value;
        if(descriptionLimit.length>45){
            descriptionLimit = descriptionLimit.substr(0, 45 - 3)+"...";
        }
        $selectedElement.find('span.description-limited').html(descriptionLimit);
        $selectedElement.find('span.description').html(formElements.$description.value);
        $selectedElement.find('span.name').html(formElements.$name.value);
        $selectedElement.find('span.street').html(formElements.$street.value);
        $selectedElement.find('span.city').html(formElements.$city.value);
        $selectedElement.find('span.province').html(formElements.$province.value);
        $selectedElement.find('span.mobile').html(formElements.$mobile.value);
        $selectedElement.find('span.telephone').html(formElements.$telephone.value);
        $selectedElement.find('span.website').html(formElements.$website.value);
        $selectedElement.find('span.email').html(formElements.$email.value);
    }

    function _onEdit(){
        $item = $(this).closest('div.segment');
        let telephone = $item.find('span.telephone').html();
        $selectedElement = $item;
        previousName = $item.find('span.name').html();

        formElements.$name.value = previousName;
        formElements.$id.value = $(this).attr('data-id');
        formElements.$description.value = $item.find('span.description').html();
        formElements.$street.value = $item.find('span.street').html();
        formElements.$city.value = $item.find('span.city').html();
        formElements.$province.value = $item.find('span.province').html();
        formElements.$mobile.value = $item.find('span.mobile').html();
        formElements.$telephone.value = (telephone)?telephone:'';
        formElements.$email.value = $item.find('span.email').html();
        formElements.$website.value = $item.find('span.website').html();
        $modal.find('#business-submit-btn').text('Save Changes');
        $('#textarea-feedback span.remaining').html(255-$item.find('span.description').text().length);
        $modal.modal('show');

    }

    function _onError( jqXhr, textStatus, errorThrown ){
        console.log( errorThrown );
        $errorMsg.text("An unexpected error as occured.");
        $errorMsg.show();
    }

    
    //functions
    function _getBusinessList(){
        let q = (this.value)?this.value.trim():'';
        let $images = [];
        $.ajax({
            url: '../../actions/business.php?search='+q+'&page='+pagination.page+'&limit='+pagination.limit,
            type: 'get',
            dataType: 'json',
            success:function(data){
                $businesses.empty();
                if(data.length == 0 && q ==""){
                    $addBusinessBtn.find("span").text("Add Your First Business");
                    return;
                }
                $(document).trigger('results', data);
                $.each(data,(i,business)=>{

                    //limit the number of characters shown for the description
                    business.limitText = function(){
                        let ret = this.description;
                        let maxLength = 45;
                        if (ret.length > maxLength) {
                            ret = ret.substr(0,maxLength-3) + "...";
                        }
                        return ret;
                    }

                    business.logo = (business.logo)?'./uploads/'+business.logo:'assets/img/img-wireframe.png';
                    business.contactQrCode = (business.contactQrCode)?'./uploads/'+business.contactQrCode:'';
                    $businesses.append(Mustache.render(businessTemplate, business));
                    let $item = $businesses.find('div.business-item-'+business.id);
                    $item.find('.ui.uploading.dimmer').addClass('active');
                    $images[i] = $item.find('img');
                    $images[i].on("load", ()=>{
                        $images[i].closest('.segment').find('.ui.uploading.dimmer').removeClass('active');
                    })
                });
                pagination.page++;
            },
            error: _onError
        })
        
    }

    function _checkIfNameExist(event){
        if( !$businessForm.form('is valid', "name") ){
          return;
        }

        $existErrorMsg.hide();
        //check name on edit
        if(previousName == $nameInput[0].value && formElements.$id.value > 0 ){
            isEmailUnique = true;
            return;
        }

        $checkingNameLoader.addClass("active");
        $.ajax({
          url: './actions/business.php?name='+$nameInput[0].value,
          dataType: 'text',
          type: 'get',
          contentType: 'application/x-www-form-urlencoded',
          success: function(data){
            $checkingNameLoader.removeClass("active");
            let result = JSON.parse(data);
            if(result){
              $existErrorMsg.show();
              isEmailUnique = false;
            }else{
              isEmailUnique = true;
            }
          },
          error: _onError
        });
      }

    //expose functions and properties to outside world
    return{
    }
})(jQuery);