import 'select2';

class StageForm {
    constructor() {
        this.subareasData = {};
        this.initializeSelect2();
        this.bindEvents();
        this.bindSubmitEvent();
    }

    initializeSelect2() {
        $('#areas').select2({
            placeholder: "Seleccione áreas",
            width: '100%',
        });
    }

    bindEvents() {
        // $('#areas').on('select2:select', this.onAreaSelect.bind(this));
        // $('#areas').on('select2:unselect', this.onAreaUnselect.bind(this));
        $("#cycle").on('change', this.onCycleChange.bind(this));
    }

    // onAreaSelect(e) {
    //     const areaId = e.params.data.id;
    //     $('#overlay').fadeIn();
    //     $.ajax({
    //         url: `/areas/get/${areaId}/subareas`,
    //         type: 'GET',
    //         success: this.handleSubareasSuccess.bind(this, areaId, e.params.data.text),
    //         error: this.handleSubareasError.bind(this),
    //     });
    // }

    // handleSubareasSuccess(areaId, areaName, data) {
    //     this.subareasData[areaId] = {
    //         areaName: areaName,
    //         subareas: data
    //     };
    //     this.updateSubareasCheckboxes();
    //     $('#subareas-container').fadeIn();
    //     $('#overlay').fadeOut();
    // }

    handleSubareasError(xhr, status, error) {
        console.error(error);
        $('#overlay').fadeOut();
    }

    // onAreaUnselect(e) {
    //     const areaId = e.params.data.id;
    //     delete this.subareasData[areaId];
    //     this.updateSubareasCheckboxes();
    // }

    onCycleChange() {
        const cycleSelected = $(this).val();
        $.ajax({
            type: 'GET',
            url: `/courses/get-by-cycle/${cycleSelected}`,
            beforeSend: this.showLoading.bind(this),
            success: this.handleCoursesSuccess.bind(this),
            error: this.handleCoursesError.bind(this),
        });
    }

    showLoading() {
        $('.loading').show();
    }

    handleCoursesSuccess(response) {
        if (response.success) {
            let stringHtml = "";
            response.courses.forEach(function (course) {
                stringHtml += `<option value="${course.id}"> ${course.name} </option>`;
            });
            $("#course").html(stringHtml);
        }
        $('.loading').hide();
    }

    handleCoursesError(error) {
        console.log('Error en la solicitud AJAX');
        console.log(error);
        $('.loading').hide();
    }

    // updateSubareasCheckboxes() {
    //     $('#subareas-checkboxes').empty();
    //     $.each(this.subareasData, (areaId, subareas) => {
    //         $('#subareas-checkboxes').append(`<h4>${subareas.areaName}</h4>`);
    //         subareas.subareas.forEach(subarea => {
    //             const checkbox = $('<div class="form-check"></div>').append(
    //                 $('<input>', {
    //                     type: 'checkbox',
    //                     class: 'form-check-input',
    //                     id: `subarea_${subarea.id}`,
    //                     name: `subareas[${areaId}][]`,
    //                     value: subarea.id
    //                 }),
    //                 $('<label>', {
    //                     class: 'form-check-label',
    //                     for: `subarea_${subarea.id}`,
    //                     text: subarea.name
    //                 })
    //             );
    //             $('#subareas-checkboxes').append(checkbox);
    //         });
    //     });
    // }

    bindSubmitEvent() {
        $("#form-stage").submit(this.onSubmit.bind(this));
    }

    onSubmit(event) {
        $("select").prop("disabled", false);
    }
}

$(document).ready(() => {
    const stageForm = new StageForm();
});
