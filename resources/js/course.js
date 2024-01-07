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


window.getStudentPreregistration = function(e){
        console.log(e.target);
        $.ajax({
            method: "GET",
            url: '/students/get-student-by-id/'+e.target.value,
            beforeSend: function() {
                $("#preloader").fadeIn(100);
            },
            dataType: 'json',
            success: function(user) {
                $("#preloader").fadeOut(100);
                let row = `<tr>
                <td>${ user.first_name+' '+user.middle_name+' '+user.last_name+' '+user.second_last_name}</td>
                <td>${ user.carnet }</td>
                <td>${ user.email }</td>
                <td><button type="button" class="btn btn-danger btn-xs remove-preregistration"><i
                            class='bx bx-trash'></i></button>
                    <input type="hidden" class="input-user_id_preregistration"
                        name="user_id_preregistration[]"
                        value="${ user.id }" />
                </td>
            </tr>`;
    
                let isEmpty = $('#tablePreregistrations tbody').find('#empty_row').length>0?true:false;
                if (isEmpty) {
                    $('#tablePreregistrations tbody').html(row);	
                }else{
                    $('#tablePreregistrations tbody').append(row);
                }
            },
            error: function(error){
                console.log(error);
            }
        });
    }

    window.getStudentRegistration = function(e){
        if (!e.target.value) {
            return;
        }
        $.ajax({
            method: "GET",
            url: '/students/get-student-by-id/'+e.target.value,
            beforeSend: function() {
                $("#preloader").fadeIn(100);
            },
            dataType: 'json',
            success: function(user) {
                $("#preloader").fadeOut(100);
                let row = `<tr>
                <td>${ user.first_name+' '+user.middle_name+' '+user.last_name+' '+user.second_last_name}</td>
                <td>${ user.carnet }</td>
                <td>${ user.email }</td>
                <td><button type="button" class="btn btn-danger btn-xs remove-preregistration"><i
                            class='bx bx-trash'></i></button>
                    <input type="hidden" class="input-user_id_registration"
                        name="user_id_registration[]"
                        value="${ user.id }" />
                </td>
            </tr>`;
    
                let isEmpty = $('#tableRegistrations tbody').find('#empty_row').length>0?true:false;
                if (isEmpty) {
                    $('#tableRegistrations tbody').html(row);	
                }else{
                    $('#tableRegistrations tbody').append(row);
                }

                $(e.target).val(null);
                $(e.target).trigger('change');
            },
            error: function(error){
                console.log(error);
            }
        });
    }


    $(document).on('click', '.remove-preregistration', function() {
	    $(this).parent().parent().remove();
	});
    $(document).on('click', '.remove-registration', function() {
	    $(this).parent().parent().remove();
	});