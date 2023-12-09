<form method="post" class="ajax-submit" autocomplete="off" action="{{ route('groups.store.autorization.agreement') }}"
    enctype="multipart/form-data">
    {{ csrf_field() }}

    <input type="hidden" name="group_committee_id" value="{{ $group_committee_id }}"/>

    <div class="row p-2">
        <div class="form-group">
            <input type="file" class="form-control" name="path_agreement" accept=".docx,.pdf,.PDF,.DOCX" required>
        </div>

        <div class="form-group mt-4">
            <div class="col-md-12 text-end">

                <button type="submit" class="btn btn-primary btn-lg"><i class="ti-save-alt"></i>&nbsp;Guardar</button>
            </div>
        </div>
    </div>
</form>
