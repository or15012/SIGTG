console.log("leido")
$(document).ready(function () {
    $('#add-student').click(function () {
        var carnet = $('#carnet').val();

        $.ajax({
            type: 'GET',
            url: '/students/get-student/' + carnet,
            success: function (response) {
                if (response.success) {
                    // Estudiante encontrado, puedes acceder a los datos en response.student
                    console.log(response.student);
                } else {
                    // Estudiante no encontrado, muestra un mensaje de error
                    console.log('Estudiante no encontrado');
                }
            },
            error: function (error) {
                console.log('Error en la solicitud AJAX');
                console.log(error);
            }
        });
    });
});


