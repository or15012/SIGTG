$(document).ready(function(){
    $('#btnGeneratePassword').on('click',function(e){
        let randomstring = Math.random().toString(36).slice(-8);
        $('#userpassword').val(randomstring);
        $('#confirmpassword').val(randomstring);
    });


    $('.show_hide_pwd').on('click', function(e){
        const togglePassword = $(this);
        const password = $(this).parent().parent().find('input');
        const type = password.attr("type") === "password" ? "text" : "password";
        password.attr("type", type);
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });
    
});

$('#excelFile').on('change', function(e){
    let files = $('#excelFile').prop('files');

    if (files.length > 0) {
        $('#btnImportar').parent().addClass('d-none');
        $('#btnCargar').parent().removeClass('d-none');
        $('#excelFile').parent().removeClass('d-none');
    }else{
        $('#btnImportar').parent().removeClass('d-none');
        $('#btnCargar').parent().addClass('d-none');
        $('#excelFile').parent().addClass('d-none');
    }
});

$('#btnImportar').on('click', ()=>$('#excelFile').click());