$(document).on("click", ".ajax-modal", function () {
    var link = $(this).data("href");
    if (typeof link == 'undefined') {
        link = $(this).attr("href");
    }

    var title = $(this).data("title");
    var fullscreen = $(this).data("fullscreen");
    var reload = $(this).data("reload");
    target_select = $(this).parent().find(".select2-ajax");

    $.ajax({
        url: link,
        beforeSend: function () {
            Swal.fire({text: 'Por favor, espere...', didOpen: () => {
                Swal.showLoading();},});
        }, success: function (data) {
            $("#preloader").css("display", "none");
            $('#main_modal .modal-title').html(title);
            $('#main_modal .modal-body').html(data);
            $("#main_modal .alert-primary").addClass('d-none');
            $("#main_modal .alert-danger").addClass('d-none');
            // $('#main_modal').modal('show');
            var myModal = new bootstrap.Modal($('#main_modal'));
            myModal.show();

            // if (fullscreen == true) {
            //     $("#main_modal >.modal-dialog").addClass("fullscreen-modal");
            // } else {
            //     $("#main_modal >.modal-dialog").removeClass("fullscreen-modal");
            // }

            if (reload == false) {
                $("#main_modal .ajax-submit").attr('data-reload', false);
            }

            //init Essention jQuery Library
            // if ($('.ajax-submit').length) {
            //     $('.ajax-submit').parsley();
            // }

            // if ($('.ajax-screen-submit').length) {
            //     $('.ajax-screen-submit').parsley();
            // }

            // init_editor();

            /** Init Datepicker **/
            // init_datepicker();

            /** Init DateTimepicker **/
            // $('.datetimepicker').daterangepicker({
            //     timePicker: true,
            //     timePicker24Hour: true,
            //     singleDatePicker: true,
            //     showDropdowns: true,
            //     locale: {
            //         format: 'YYYY-MM-DD HH:mm'
            //     }
            // });


            $(".float-field").keypress(function (event) {
                if ((event.which != 46 || $(this).val().indexOf('.') != -1) &&
                    (event.which < 48 || event.which > 57)) {
                    event.preventDefault();
                }
            });

            $(".int-field").keypress(function (event) {
                if ((event.which < 48 || event.which > 57)) {
                    event.preventDefault();
                }
            });


            //Select2
            if ($("#main_modal select.select2").length > 0) {
                $("#main_modal select.select2").select2({
                    dropdownParent: $("#main_modal .modal-content")
                });
            }

            //Ajax Select2
            // if ($("#main_modal .select2-ajax").length) {
            //     $('.select2-ajax').each(function (i, obj) {

            //         var display2 = "";
            //         var where = "";
            //         if (typeof $(this).data('display2') !== "undefined") {
            //             display2 = "&display2=" + $(this).data('display2');
            //         }
                    
            //         if (typeof $(this).data('where') !== "undefined") {
            //             where = "&where=" + $(this).data('where');
            //         }

            //         if($(this).closest('[role="dialog"]')[0] != undefined){
            //             parent = $(this).closest('[role="dialog"] .modal-content');
            //         }

            //         $(this).select2({
            //             dropdownParent: parent,
            //             ajax: {
            //                 url: _url + '/ajax/get_table_data?table=' + $(this).data('table') + '&value=' + $(this).data('value') + '&display=' + $(this).data('display') + display2 + where,
            //                 data: function (params) {
            //                     return {
            //                         term: params.term || '',
            //                         page: params.page || 1,
            //                         results: params
            //                     }
            //                     // return {
            //                     // 	results: data
            //                     // };
            //                 },
            //                 cache: true
            //                 // processResults: function (data) {
            //                 // 	return {
            //                 // 		results: data
            //                 // 	};
            //                 // }
            //             }
            //         });
            //     });
            // }

            //Auto Selected
            if ($(".auto-select").length) {
                $('.auto-select').each(function (i, obj) {
                    $(this).val($(this).data('selected')).trigger('change');
                })
            }


            // $(".dropify").dropify();
            $("#main_modal .ajax-submit input:required, #main_modal .ajax-submit select:required, #main_modal .ajax-submit textarea:required").closest(".form-group").find('.control-label').append("<span class='required'> *</span>");
            $("#main_modal .ajax-screen-submit input:required, #main_modal .ajax-screen-submit select:required, #main_modal .ajax-screen-submit textarea:required").closest(".form-group").find('.control-label').append("<span class='required'> *</span>");
        },
        error: function (request, status, error) {
            console.log(request.responseText);
        },complete: function(){
            Swal.close();
        }
    });

    return false;
});