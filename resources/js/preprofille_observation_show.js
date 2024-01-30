$(document).ready(function () {

    $("#accept-preprofile").click(function () {
        $("#decision").val("1"); // Cambiar el valor a 1 antes de enviar el formulario acepta invitacion
        $("#form-preprofile-confirm").submit(); // Enviar el formulario
    });

    $("#review-preprofile").click(function () {
        $("#decision").val("2"); // Cambiar el valor a 1 antes de enviar el formulario acepta invitacion
        $("#form-preprofile-confirm").submit(); // Enviar el formulario
    });

    $("#deny-preprofile").click(function () {
        $("#decision").val("3"); // Cambiar el valor a 2 antes de enviar el formulario deniega invitacion
        $("#form-preprofile-confirm").submit(); // Enviar el formulario
    });
});
