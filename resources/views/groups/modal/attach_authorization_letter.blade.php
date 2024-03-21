<form method="post" class="ajax-submit" autocomplete="off" action="{{ route('groups.store.autorization.letter') }}"
    enctype="multipart/form-data">
    {{ csrf_field() }}

    <input type="hidden" name="group_id" value="{{ $group_id }}" />

    <div class="row p-2">
        <div class="mb-3">
            <label for="cartaautorizacion">Carta de autorización</label>
            <input type="file" class="form-control" name="authorization_letter" accept=".docx,.pdf,.PDF,.DOCX"
                required>
        </div>
       @include('layouts.agreement_include')
        @if ($countUserGroup > 5)
            <div class="mb-3">
                <label for="carta grupos">Carta de autorización para grupos mayores a 5 integrantes</label>
                <input type="file" class="form-control" name="authorization_letter_higher_members"
                    accept=".docx,.pdf,.PDF,.DOCX" required>
            </div>
        @endif
        <div class="form-group mt-4">
            <div class="col-md-12 text-end">
                <button type="submit" class="btn btn-primary btn-lg"><i class="ti-save-alt"></i>&nbsp;Guardar</button>
            </div>
        </div>
    </div>
</form>
