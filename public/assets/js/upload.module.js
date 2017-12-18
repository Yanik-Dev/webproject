let UploadModule = {
    files: [],
    extensions:[],
    maxSize:2000000,
    errorElement: null,
    validate: function(){
        for(let i=0; i <files.length; i++){
            $uploadErrorMsg.find('span').text("File cannot exceed 2MB.");
        }
    },
    upload: function(url){
        $.ajax({
            url: '../../actions/business.php?id='+$this.closest('.item').find('.editBtn').attr("data-id"),
            type: 'POST',
            dataType: 'text',
            contentType: false,
            processData: false,
            data: formData,
            success:function(data){
                
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
}
        let imgPath = $(this)[0].value;
        let $this = $(this);
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
           
        }
        reader.readAsDataURL($(this)[0].files[0]);
