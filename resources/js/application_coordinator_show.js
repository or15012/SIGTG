$(document).ready(function () {

    $("#accept-application").click(function () {
        $("#decision").val("1"); // Cambiar el valor a 1 antes de enviar el formulario acepta invitación
        $("#form-application-confirm").submit(); // Enviar el formulario
    });

    $("#deny-application").click(function () {
        $("#decision").val("2"); // Cambiar el valor a 2 antes de enviar el formulario rechaza invitación
        $("#form-application-confirm").submit(); // Enviar el formulario
    });
});

