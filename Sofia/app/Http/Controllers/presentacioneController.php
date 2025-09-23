<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePresentacioneRequest;
use App\Http\Requests\UpdatePresentacioneRequest;
use App\Models\Caracteristica;
use App\Models\Presentacione;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB; 

class presentacioneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
          $presentaciones = Presentacione::with('caracteristica')->latest()->get();
        //dd($marcas);
        return view('presentacione.index', ['presentaciones' => $presentaciones  ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('presentacione.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePresentacioneRequest $request)
    {
        //dd($request);
        try {
            FacadesDB::beginTransaction();
            $caracteristica = Caracteristica::create($request->validated());
            $caracteristica->presentacione()->create([
                'caracteristica_id' => $caracteristica->id
            ]);
            FacadesDB::commit();
        } catch (\Exception $e) {
            FacadesDB::rollBack();            
        }

        return redirect()->route('presentaciones.index')->with('success', 'Presentación creada exitosamente.');
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
       public function edit(Presentacione $presentacione)
    {
                //dd($presentacione);

        return view('presentacione.edit', ['presentacione' => $presentacione]);
    }

    /**
     * Update the specified resource in storage.
     */
        public function update(UpdatePresentacioneRequest $request, Presentacione $presentacione)
    {
        Caracteristica::where('id', $presentacione->caracteristica->id)
        ->update($request->validated());

        return redirect()->route('presentaciones.index')->with('success', 'Presentación actualizada exitosamente.');   
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //dd($id);
        $message = '';

        $presentacione = Presentacione::find($id);
        if ($presentacione->caracteristica->estado == 1) {
            Caracteristica::where('id', $presentacione->caracteristica->id)
                ->update([
                    'estado' => '0'
                ]);
            $message = 'Presentación eliminada';
        } else {
            Caracteristica::where('id', $presentacione->caracteristica->id)
                ->update([
                    'estado' => '1'
                ]);
            $message = 'Presentación restaurada';
        }
              

        return redirect()->route('presentaciones.index')->with('success', $message);
    }
}
