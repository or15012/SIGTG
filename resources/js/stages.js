$(document).ready(function () {
    console.log("a")
    $("#form-stage").submit(function () {
        // Deshabilitar el select antes de enviar el formulario
        $("select").prop("disabled", false);
    });
});
