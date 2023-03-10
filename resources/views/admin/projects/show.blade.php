@extends('layouts.app')
@section('title', $project->name)

@section('content')
<header>
    <h1 class="my-5">{{ $project->name }}</h1>
</header>
<div class="clearfix">
    @if($project->image)
        <img class="me-2 img-fluid float-start" src="{{ asset('storage/' . $project->image) }}" alt="{{ $project->name }}">
    @endif
    <p>{{ $project->description }}</p>
    <div><strong>Creato: </strong><time>{{ $project->created_at }}</time>
        <strong class="ms-3"> Ultima modifica: </strong><time>{{ $project->created_at }}</time>
        <strong class="ms-3"> Stato: </strong>{{ $project->is_published ? 'Pubblicato' : 'Bozza' }}
        <strong class="ms-3"> Tipo: </strong>{{ $project->type?->label }}
    </div>
</div>
    <hr>
    <div class="d-flex justify-content-end">

        <form action="{{ route('admin.projects.toggle', $project->id) }}" method="POST">
            @method('PATCH')
            @csrf
            <button type="submit" class="me-2 btn btn-outline-{{ $project->is_published ? 'danger' : 'success'}}">
                {{ $project->is_published ? 'Mettin in bozze' : 'Pubblica' }}
            </button>
          </form>

        <a class="btn btn-outline-secondary me-2" href="{{ route('admin.projects.edit', $project->id) }}"><i class="fas fa-pencil me-2"></i>Modifica</a>
        <form method="POST" action="{{ route('admin.projects.destroy', $project->id) }}" class="delete-form" data-name="progetto">
            @csrf
            @method('DELETE')
            <button class=" me-2 btn btn-outline-secondary" type="submit"><i class="fas fa-trash me-2"></i>Elimina</button>
          </form>
        <a class="btn btn-outline-secondary" href="{{ route('admin.projects.index') }}"><i class="fas fa-arrow-left me-2"></i>Indietro</a>
    </div>
@endsection