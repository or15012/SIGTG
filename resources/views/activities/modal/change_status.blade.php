<form method="post" class="ajax-submit" autocomplete="off" action="{{ route('activities.status', ['activity' => $activity])}}">
    @method('PUT')
    @csrf

    <div class="row mb-3">
        <div class="col-12">
            <label for="status" class="form-label">Estado</label>
            <select class="form-select" name="status" required>
                <option value="Pendiente" {{ $activity->status == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="En curso" {{ $activity->status == 'En curso' ? 'selected' : '' }}>En curso</option>
                <option value="Completada" {{ $activity->status == 'Completada' ? 'selected' : '' }}>Completada</option>
            </select>
            </select>
        </div>
    </div>

    <div class="form-group mt-4">
        <div class="col-md-12 text-end">

            <button type="submit" class="btn btn-primary btn-lg"><i class="ti-save-alt"></i>&nbsp;Guardar</button>
        </div>
    </div>
    </div>
</form>
