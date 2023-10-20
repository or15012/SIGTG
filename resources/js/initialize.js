$(document).ready(function () {
    let students = [];
    $('#add-student').click(function () {
        var carnet = $('#carnet').val();
        //validare que estudiante no se encuentre en grupo actual

        var carnetExiste = students.some(function(item) {
            return item.carnet === carnet;
        });
        if (carnetExiste) {
            // El carnet ya existe en el arreglo retornare sin ir a buscarlo
            console.log('El carnet ya existe en el arreglo.');
            return false;
        }

        //buscare el usuario
        $.ajax({
            type: 'GET',
            url: '/students/get-student/' + carnet,
            beforeSend: function () {
                $('.loading').show();
            },
            success: function (response) {
                if (response.success) {
                    // Estudiante encontrado, puedes acceder a los datos en response.student
                    let user = response.student;



                    //añadire nuevo integrante
                    students.push(user);
                    let stringHtml = `
                        <div class="col-12 col-md-6 col-lg-6 ">
                            <div class="card mb-4">
                                <div class="card-header">${user.carnet} - ${user.first_name} ${user.middle_name} ${user.last_name} ${user.second_last_name}</div>
                                <div class="card-body">
                                <input type="hidden" name="users[]" value="${user.id} ">
                                </div>
                            </div>
                        </div>`;

                    $("#list-group").append(stringHtml);

                } else {
                    // Estudiante no encontrado, muestra un mensaje de error
                    console.log('Estudiante no encontrado');
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


