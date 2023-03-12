<?php

namespace App\Http\Controllers\Admin;

use App\Models\Project;
use App\Models\Type;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;



class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       $status_filter = $request->query('status_filter');
       $type_filter = $request->query('type_filter');

       $query = Project::orderBy('updated_at', 'DESC');

       if($status_filter) {
         $value = $status_filter === 'published';
         $query->where('is_published', $value);
       }
       if($type_filter) {
        $query->where('type_id', $type_filter);
      }

       $projects =$query->paginate(10);
       $types = Type::all();

       return view('admin.projects.index', compact('projects', 'types', 'status_filter', 'type_filter'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       $project = new Project();
       $types =  Type::orderBy('label')->get();
       return view('admin.projects.create', compact('project', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|unique:projects',
            'description' => 'required|string',
            'image' => 'nullable|image',
            'prog_url'=> 'nullable|url',
            'type_id'=> 'nullable|exists:types,id'
        ], [
            'name.unique' => "Il Nome $request->name è già presente",
            'name.required' => 'Il campo Nome è obbligatorio',
            'description.required' => 'Il campo Contenuto è obbligatorio',
            'image.image' => 'Inserire un link valido',
            'prog_url.url'=> 'Inserire un link valido',
            'type_id'=> 'Tipo non valido'

        ]);

        $data = $request->all();
        $project = new Project();

        if(Arr::exists($data, 'image')){
            $img_url = Storage::put('projects', $data ['image']);
            $data['image'] = $img_url;
        }

        $data['is_published'] = Arr::exists($data, 'is_published');

        $project->fill($data);

        $project->save();

        return to_route('admin.projects.show', $project->id)->with('type', 'success')->with('msg','Nuovo progetto creato con successo!');

    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        return to_route('admin.projects.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
       $types =  Type::orderBy('label')->get();

       return view('admin.projects.edit', compact('project','types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {

        $request->validate([
            'name' => ['required', 'string', Rule::unique('projects')->ignore($project->id)],
            'description' => 'required|string',
            'image' => 'nullable|image',
            'prog_url'=> 'nullable|url',
            'type_id'=> 'nullable|exists:types,id'

        ], [
            'name.unique' => "Il Nome $request->name è già presente",
            'name.required' => 'Il campo Nome è obbligatorio',
            'description.required' => 'Il campo Contenuto è obbligatorio',
            'image.image' => 'Inserire un link valido',
            'prog_url.url'=> 'Inserire un link valido',
            'type_id'=> 'Tipo non valido'

        ]);

        $data = $request->all();

        if(Arr::exists($data, 'image')){
            if($project->image) Storage::delete($project->image);
            $img_url = Storage::put('projects', $data ['image']);
            $data['image'] = $img_url;
        }
        
        $data['is_published'] = Arr::exists($data, 'is_published');

        $project->update($data);

        return to_route('admin.projects.show', $project->id)->with('type', 'success')->with('msg', "Il progetto è stato modificato con successo");
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {

      if($project->image) Storage::delete($project->image);
      $project->delete();
      return to_route('admin.projects.index')->with('type', 'danger')->with('msg', "Il progetto '$project->name' è stato cancellato con successo");
    }

    public function toggle(Project $project){

        $project->is_published = !$project->is_published;

        $action =  $project->is_published ? 'pubblicato con successo' : 'salvato in bozze';
        $type =  $project->is_published ? 'success' : 'info';

        $project->save();

        return redirect()->back()->with('type', $type)->with('msg', "Il progetto è stato $action ");
    }
}
