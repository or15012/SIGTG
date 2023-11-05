import select2 from 'select2';


$(document).ready(function() {
    $('#miSelect').select2({
        dropdownParent: $('#searchModal') // Reemplaza 'tu-modal' con el selector de tu modal
    });
});
