$('.add-image-btn').click(function() {
    var name = $(this).data('name');
    $('#' + name + '-btm').before("<div class='form-group col-md-3'><input type='file' name='" + name + "[]' id='input-file-max-fs' class='dropify' data-height='160px' data-max-file-size='2M' data-allowed-file-extensions='png jpg jpeg bmp gif' /></div>");
    $('.dropify').dropify();
});