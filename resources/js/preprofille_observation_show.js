$(document).ready(function () {

    $("#accept-preprofile").click(function () {

        $("#decision").val("1"); // Cambiar el valor a 1 antes de enviar el formulario acepta invitacion
        mostrarConfirmacion();
    });

    $("#review-preprofile").click(function () {
        $("#decision").val("2"); // Cambiar el valor a 1 antes de enviar el formulario acepta invitacion
        $("#form-preprofile-confirm").submit(); // Enviar el formulario
    });

    $("#deny-preprofile").click(function () {
        $("#decision").val("3"); // Cambiar el valor a 2 antes de enviar el formulario deniega invitacion
        $("#form-preprofile-confirm").submit(); // Enviar el formulario
    });

    function mostrarConfirmacion() {
        Swal.fire({
            title: 'Nota de planificación',
            text: "No podrás revertir esto",
            input: "text",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, cargar nota y aprobar',
            inputValidator: (value) => {
                // Validar que la entrada sea un número decimal
                if (!/^\d*\.?\d+$/.test(value)) {
                    return 'Por favor, ingresa un número decimal válido';
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                let valorIngresado = result.value;
                let valorParseado = parseFloat(valorIngresado).toFixed(2);
                $("#note").val(valorParseado);
                $("#form-preprofile-confirm").submit(); // Enviar el formulario
            }
        });
    }
});
