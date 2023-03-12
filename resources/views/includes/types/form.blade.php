@if ($type->exists)
  <form action="{{ route('admin.types.update', $type->id) }}" method="POST" enctype="multipart/form-data">
    @method('PUT')
  @else
   <form action="{{ route('admin.types.store') }}" method="POST" enctype="multipart/form-data">
@endif

@csrf
    <div class="row">
        <div class="col-md-11">
            <div class="mb-3">
                <label for="label" class="form-label">Label</label>
                <input type="text" class="form-control @error('label') is-invalid @enderror" id="label" name="label" value="{{ old('label', $type->label) }}">
                @error('label')
                    <div class="invalid-feedback">{{ $message }}</div>
                @else
                    <div class="text-muted">Inserisci il Nome</div>
                @enderror
            </div>    
        </div>
        <div class="col-md-1">
            <div class="mb-3">
                <label for="color" class="form-label">Colore</label>
                <input type="color" class="form-control" id="color" name="color" value="{{ old('color', $type->color) }}">
            </div>    
        </div>
    </div>
    
    <div class="d-flex justify-content-end">
        <a href="{{ route('admin.types.index')}}" class="btn btn-outline-secondary me-2"><i class="fas fa-arrow-left me-2"></i>Indietro</a>    
        <button type="submit" class="btn btn-outline-secondary"><i class="fas fa-floppy-disk me-2"></i>Salva</button>    
    </div>  
  </form>
