$(document).ready(function () {
    console.log("click");
    $("#accept-group").click(function () {
        console.log("click")
        $("#decision").val("1"); // Cambiar el valor a 1 antes de enviar el formulario acepta invitacion
        $("#form-group-confirm").submit(); // Enviar el formulario
    });

    $("#deny-group").click(function () {
        $("#decision").val("2"); // Cambiar el valor a 2 antes de enviar el formulario deniega invitacion
        $("#form-group-confirm").submit(); // Enviar el formulario
    });
});
