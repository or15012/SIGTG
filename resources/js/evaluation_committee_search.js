import select2 from 'select2';
import Swal from 'sweetalert2';


$(document).ready(function () {

    $('#teachers').select2({
        placeholder: "Seleccione docentes",
        allowClear: true,
        width: '100%',
    });
    // Swal.fire({
    //     title: '¡Hola!',
    //     text: 'Esta es una alerta personalizada desde Blade con SweetAlert2.',
    //     icon: 'success',
    //     confirmButtonText: 'OK'
    //   });

    // $('#miSelect').select2({
    //     minimumInputLength: 3,
    //     placeholder: "Ingrese nombre de docente.",
    //     allowClear: false,
    //     enable: true,
    //     readonly: false,
    //     multiple: true,
    //     width: '100%',
    //     ajax: {
    //         url: '/groups/evaluating-committee-get',
    //         dataType: 'json',
    //         type: "GET",
    //         quietMillis: 50,
    //         data: function (term) {
    //             return {
    //                 term: term
    //             };
    //         },
    //         processResults: function (data) {
    //             console.log("respues ajax busqueda docentes")
    //             return {
    //                 results: $.map(data, function (item) {
    //                     console.log(item[0].text);
    //                     return {
    //                         text: item[0].text,
    //                         id: item[0].id
    //                     }
    //                 })
    //             };
    //         }
    //     }
    // });
});
