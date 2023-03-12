<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Type;
use Illuminate\Validation\Rule;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $types = Type::paginate(10);
       return view('admin.types.index', compact('types'));
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       $type = new Type();

       return view('admin.types.create',compact('type')); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'label' => 'required|string|unique:types',
            'color' => 'nullable|string|size:7',
          
        ], [
            'label.required' => 'Il tipo deve avere un label',
            'label.unique' => 'Esiste già un tipo con questo nome',
            'color.size' => 'Il colore deve essere un codice esadecimale con cancelletto',    
        ]);

        $data = $request->all();
        $type = new Type();
        $type->fill($data);
        $type->save();

        return to_route('admin.types.index')->with('type', 'success')->with('msg','Nuovo tipo creato con successo!');

            
    }

    /**
     * Display the specified resource.
     */
    public function show(Type $type)
    {
        return view('admin.types.index', compact('type'));
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Type $type)
    {
        return view('admin.types.edit', compact('type'));
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Type $type)
    {
        $request->validate([
            'label' => ['required', 'string', Rule::unique('types')->ignore($type->id)],
            'color' => 'nullable|string|size:7',
          
        ], [
            'label.required' => 'Il tipo deve avere un label',
            'label.unique' => 'Esiste già un tipo con questo nome',
            'color.size' => 'Il colore deve essere un codice esadecimale con cancelletto',    
        ]);

        $data = $request->all();

        $type->update($data);

        return to_route('admin.types.index')->with('type', 'success')->with('msg','Tipo modificato con successo!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Type $type)
    {
        $type->delete();
        return to_route('admin.types.index')->with('type', 'success')->with('msg', "Tipo '$type->label' cancellato con successo");
      }
    }
