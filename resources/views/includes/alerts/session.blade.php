@if (session('msg'))
  <div class="alert alert-{{ session('type') ?? 'info' }} mt-4">
    {{ session('msg') }}
  </div>
@endif