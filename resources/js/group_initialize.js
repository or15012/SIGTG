$(document).ready(function () {


    let students = [];

    $("#list-group").on("click", ".delete-user", function() {
        // Obtener el valor del atributo "data-user" del botón
        var userId = $(this).data("user");
        $("#user-" + userId).remove();
        // Realizar las acciones que desees con userId
        console.log("Se hizo clic en el botón con el data-user: " + userId);
    });

    $('#add-student').click(function () {
        var carnet = $('#carnet').val();
        //validare que estudiante no se encuentre en grupo actual

        var carnetExiste = students.some(function (item) {
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
                                <div class="card-header">
                                    ${user.carnet} - ${user.first_name} ${user.middle_name} ${user.last_name} ${user.second_last_name}
                                </div>
                                <div class="card-body">

                                    <input type="hidden" name="users[]" value="${user.id} ">
                                    <div>
                                        <label class="my-1 fw-bold">Estado de asignación</label>
                                    </div>
                                    <div>
                                        <label class="my-1 bg-soft-secondary text-opacity-100 p-2 rounded">
                                            Pendiente de envío.
                                        </label>
                                    </div>
                                    <button type="button" data-user="${user.id}" class="delete-user btn btn-danger waves-effect waves-light">
                                        <i class="fas fa-window-close"></i>
                                    </button>
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


