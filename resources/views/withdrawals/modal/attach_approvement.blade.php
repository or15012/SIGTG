<form method="post" class="ajax-submit" autocomplete="off" action="{{ route('withdrawals.coordinator.store.approvement') }}"
    enctype="multipart/form-data">
    {{ csrf_field() }}

    <input type="hidden" name="withdrawal_id" value="{{ $withdrawal_id }}"/>

    <div class="row p-2">
        @include('layouts.agreement_include');

        <div class="form-group mt-4">
            <div class="col-md-12 text-end">

                <button type="submit" class="btn btn-primary btn-lg"><i class="ti-save-alt"></i>&nbsp;Guardar</button>
            </div>
        </div>
    </div>
</form>
