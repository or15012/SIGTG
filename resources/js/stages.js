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
        $("#cycle").on('change', this.onCycleChange.bind(this));
    }


    handleSubareasError(xhr, status, error) {
        console.error(error);
        $('#overlay').fadeOut();
    }


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
