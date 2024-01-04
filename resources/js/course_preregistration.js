jQuery(function(){
    $('.form-check-input').on('change', function(){
        if (this.checked) {
            $(this).val(1);
            $(this).parent().find('.checkhidden').attr('disabled', 'disabled');
        } else {
            $(this).val(0);
            $(this).parent().find('.checkhidden').removeAttr('disabled');
        }
    });
});