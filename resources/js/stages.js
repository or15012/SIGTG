$(document).ready(function () {
    console.log("a")
    $("#form-stage").submit(function () {
        // Deshabilitar el select antes de enviar el formulario
        $("select").prop("disabled", false);
    });


    $("#cycle").on('change', function () {
        let cycleSelected = $(this).val();

        //buscare el usuario
        $.ajax({
            type: 'GET',
            url: '/courses/get-by-cycle/' + cycleSelected,
            beforeSend: function () {
                $('.loading').show();
            },
            success: function (response) {
                if (response.success) {
                    // Estudiante encontrado, puedes acceder a los datos en response.student
                    let stringHtml = "";

                    response.courses.forEach(function(course) {
                        // Access each course using the 'course' variable
                        stringHtml = stringHtml + `<option value="${course.id}"> ${course.name} </option>`;
                    });

                    $("#course").html(stringHtml);

                }

                $('.loading').hide();
            },
            error: function (error) {
                $('.loading').hide();
                console.log('Error en la solicitud AJAX');
                console.log(error);
            }
        });
    });

});
