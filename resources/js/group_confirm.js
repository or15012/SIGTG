
$(document).ready(function () {

    $("#accept-invitation").click(function () {
        $("#decision").val("1"); // Cambiar el valor a 1 antes de enviar el formulario
        $("#form-group-confirm").submit(); // Enviar el formulario
    });

    $("#deny-invitation").click(function () {
        $("#decision").val("2"); // Cambiar el valor a 2 antes de enviar el formulario
        $("#form-group-confirm").submit(); // Enviar el formulario
    });
});
