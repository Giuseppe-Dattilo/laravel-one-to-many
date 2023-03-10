@extends('layouts.app')
@section('title', 'Projects')
    
@section('content')
<header class="d-flex align-items-center justify-content-between">
    <h1 class="mt-5">Projects</h1>
    <div class="d-flex align-items-center justify-content-end mt-5">
        <form action="{{ route ('admin.projects.index')}}" method="GET">
          <div class="input-group">
            <button class="btn btn-outline-secondary" type="submit">Filtra</button>
            <select class="form-select" name="status_filter">
              <option selected value="">Tutti</option>
              <option @if ($status_filter === 'published') selected @endif value="published">Pubblicati</option>
              <option @if ($status_filter === 'drafts') selected @endif value="drafts">Bozze</option>
            </select> 
            <select class="form-select" name="type_filter" id="type_filter">
              <option value="">Tutti i tipi</option>
              @foreach ($types as $type)
              <option @if($type_filter == $type->id) selected @endif value="{{ $type->id }}">{{ $type->label }}</option> 
              @endforeach
            </select>
          </div>
        </form>
      <a href="{{ route('admin.projects.create') }}" class="btn btn-success ms-3"><i class="fas fa-plus me-2"></i>Crea nuovo</a>
    </div>
</header>
<hr>
<table class="table">
    <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Titolo</th>
          <th scope="col">Tipo</th>
          <th scope="col">Pubblicato</th>
          <th scope="col">Creato</th>
          <th scope="col">Aggiornato</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($projects as $project)
        <tr>
            <th scope="row">{{ $project->id }}</th>
            <td>{{ $project->name }}</td>
            <td>
              @if($project->type)
              <span class="badge" 
                style="background-color: {{ $project->type->color }}">{{$project->type->label}}</span>
              @else -
              @endif
            </td>
            <td>
              <form action="{{ route('admin.projects.toggle', $project->id) }}" method="POST">
                @method('PATCH')
                @csrf
                <button type="submit" class="btn btn-outline py-0">
                  <i class="fas fa-toggle-{{ $project->is_published ? 'on' : 'off' }} {{ $project->is_published ? 'text-success' : 'text-danger' }}"></i>
                </button>
              </form>
            </td>
            <td>{{ $project->created_at }}</td>
            <td>{{ $project->updated_at }}</td>
            <td class="d-flex justify-content-end align-items-center">
                <a href="{{ route('admin.projects.show', $project->id) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></a>
                <a class="btn btn-outline-warning ms-2 btn-sm" href="{{ route('admin.projects.edit', $project->id) }}"><i class="fas fa-pencil"></i></a>
                <form method="POST" action="{{ route('admin.projects.destroy', $project->id) }}" class="delete-form" data-name="progetto">
                  @csrf
                  @method('DELETE')
                  <button class=" ms-2 btn btn-sm btn-outline-danger" type="submit"><i class="fas fa-trash"></i></button>
                </form>
            </td>
          </tr>
        @empty
        <tr>
            <th scope="row" colspan="5" class="text-center">Non ci sono progetti</th>
        </tr>
        @endforelse
      </tbody>
</table>
<div class="d-flex justify-content-end">
  @if( $projects->hasPages())
  {{ $projects->links()}}
  @endif
</div>
<section id="type-projects my-5">
  <hr>
  <h2 class="mb-5 mt-5">Projects per tipo</h2>
  <div class="row">
    @foreach ($types as $type)
    <div class="col mb-5">
      <strong><h5 class="my-3">{{ $type->label }}</strong> <small>({{count($type->projects)}})</small></h5>
      @forelse ($type->projects as $project)
          <div><a href="{{ route ('admin.projects.show', $project->id)}}">{{$project->name}}</a></div>
      @empty
      Nessun progetto
      @endforelse
    </div>
    @endforeach
  </div>
</section>
<hr>

@endsection