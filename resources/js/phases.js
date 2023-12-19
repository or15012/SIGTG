
import Sortable from 'sortablejs';

var form = document.getElementById('assignment-form');
var stages = document.getElementById('stages');
var assignStages = document.getElementById('assign-stages');
var formData = new FormData();

var sortableStagesList = new Sortable(stages, {
    group: {
        name: "stages-list",
        pull: true,
        put: true
    }, // set both lists to same group
    animation: 150,
    easing: "cubic-bezier(0.895, 0.03, 0.685, 0.22)",
    chosenClass: "bg-primary",
});

var sortableAssignStages = new Sortable(assignStages, {
    group: {
        name: "stages-list",
        pull: true,
        put: true
    },
    animation: 150,
    easing: "cubic-bezier(0.895, 0.03, 0.685, 0.22)",
    chosenClass: "bg-primary",
});


// Actualizar inputs ocultos al cambiar la lista de áreas asignadas
sortableAssignStages.option('onEnd', function (evt) {
    updateAssignedStagesInputs();
});

sortableStagesList.option('onEnd', function (evt) {
    updateAssignedStagesInputs();
});

function updateAssignedStagesInputs() {
    var container = $('#input-container');
    container.empty();
    $("#assign-stages li").each(function (index, element) {
        var dataIdValue = $(this).data('id');
        var newInput = $('<input>').attr({
            type: 'hidden',
            name: `stages[${index}]`,
            value: dataIdValue
        });
        console.log(newInput);
        // Agregar el nuevo input al contenedor
        container.append(newInput);
    });


}


// Enviar formulario al servidor
form.addEventListener('submit', function (event) {
    // Asegurarse de que los inputs estén actualizados antes de enviar el formulario
    event.preventDefault();
    updateAssignedStagesInputs();
    console.log('pase')

    this.submit();
});
