
$(document).ready(function () {

    function calculateGrade() {
        $('tbody.notes tr').each(function () {
            var totalGrade = 0;
            var userId = $(this).data('value');

            // Recorrer las notas de cada criterio para este usuario
            $(this).find('label.note').each(function () {
                var note = parseFloat($(this).html());
                var percentage = parseFloat($(this).data('percentage'));
                // Calcular la contribución de esta nota al total según el porcentaje del criterio
                var contribution = (note * percentage) / 100;
                totalGrade += contribution;
            });
            // Establecer la nota final en el último td de la fila
            $(this).find('.final-note-' + userId).html(totalGrade.toFixed(2));
        });
    }
    calculateGrade();

    $('tbody').on('input', 'input.note', function () {
        calculateGrade();
    });
});
