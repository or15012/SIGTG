$(document).ready(function () {
    $("#form-area").submit(function () {
        // Deshabilitar el select antes de enviar el formulario
        $("select").prop("disabled", false);
    });
});
