$(document).ready(function () {

    $("#accept-profile").click(function () {
        console.log("click")
        $("#decision").val("1"); // Cambiar el valor a 1 antes de enviar el formulario acepta invitacion
        $("#form-profile-confirm").submit(); // Enviar el formulario
    });

    $("#review-profile").click(function () {
        console.log("click")
        $("#decision").val("2"); // Cambiar el valor a 1 antes de enviar el formulario acepta invitacion
        $("#form-profile-confirm").submit(); // Enviar el formulario
    });

});
