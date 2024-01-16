$(document).ready(function () {
    console.log("ejecute");

    $("#accept-application").click(function () {
        console.log("Accept button clicked");
        $("#decision").val("1"); // Cambiar el valor a 1 antes de enviar el formulario acepta invitación
        console.log("Decision value:", $("#decision").val()); // Verificar el valor de decision
        $("#form-application-confirm").submit(); // Enviar el formulario
    });

    $("#deny-application").click(function () {
        console.log("Deny button clicked");
        $("#decision").val("2"); // Cambiar el valor a 2 antes de enviar el formulario rechaza invitación
        console.log("Decision value:", $("#decision").val()); // Verificar el valor de decision
        $("#form-application-confirm").submit(); // Enviar el formulario
    });
});

