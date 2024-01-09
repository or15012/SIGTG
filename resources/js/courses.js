$(document).ready(function () {
    $("#course-form").submit(function () {
        // Deshabilitar el select antes de enviar el formulario
        $("select").prop("disabled", false);
    });
});
