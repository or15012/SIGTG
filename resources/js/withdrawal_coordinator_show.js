$(document).ready(function () {

    // $("#accept-withdrawal").click(function () {
    //     $("#decision").val("1"); // Cambiar el valor a 1 antes de enviar el formulario acepta invitación
    //     $("#form-withdrawal-confirm").submit(); // Enviar el formulario
    // });

    $("#deny-withdrawal").click(function () {
        $("#decision").val("2"); // Cambiar el valor a 2 antes de enviar el formulario rechaza invitación
        $("#form-withdrawal-confirm").submit(); // Enviar el formulario
    });
});

