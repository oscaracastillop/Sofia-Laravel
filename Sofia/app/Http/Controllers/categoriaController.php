<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoriaRequest;
use App\Http\Requests\UpdateCategoriaRequest;
use App\Models\Caracteristica;
use App\Models\Categoria;
use DragonCode\Support\Facades\Facade;
use Illuminate\Container\Attributes\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;

class categoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categorias = Categoria::with('caracteristica')->latest()->get();
        //dd($categorias);
        return view('categoria.index', ['categorias' => $categorias  ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categoria.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoriaRequest $request)
    {
        //dd($request);
        try {
            FacadesDB::beginTransaction();
            $caracteristica = Caracteristica::create($request->validated());
            $caracteristica->categoria()->create([
                'caracteristica_id' => $caracteristica->id
            ]);
            FacadesDB::commit();
        } catch (\Exception $e) {
            FacadesDB::rollBack();            
        }

        return redirect()->route('categorias.index')->with('success', 'Categoria creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categoria $categoria)
    {
        //dd($categoria);

        return view('categoria.edit', ['categoria' => $categoria]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoriaRequest $request, Categoria $categoria)
    {
        Caracteristica::where('id', $categoria->caracteristica->id)
        ->update($request->validated());

        return redirect()->route('categorias.index')->with('success', 'Categoria actualizada exitosamente.');   
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //dd($id);
        $message = '';

        $categoria = Categoria::find($id);
        if ($categoria->caracteristica->estado == 1) {
            Caracteristica::where('id', $categoria->caracteristica->id)
                ->update([
                    'estado' => '0'
                ]);
            $message = 'Categoría eliminada';
        } else {
            Caracteristica::where('id', $categoria->caracteristica->id)
                ->update([
                    'estado' => '1'
                ]);
            $message = 'Categoría restaurada';
        }
              

        return redirect()->route('categorias.index')->with('success', $message);
    }
}
