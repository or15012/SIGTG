$(document).ready(function () {



    $("#submit-final-stage").click(function () {

        mostrarConfirmacion();
    });

    function mostrarConfirmacion() {
        Swal.fire({
            title: 'Nota de memormia de capitalización',
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
                $("#projects-approve-stage").submit(); // Enviar el formulario
            }
        });
    }
});
