<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libro;

class LibroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $libros = Libro::all();
        return  response()->json(["libros" => $libros]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validacion normal
        /*$this->validate($request, [
            "nombre" => "required",
            "descripcion" => "required"
        ]);*/

        //mensajes personalizados
        $rules = [
            'nombre' => 'required',
            'descripcion' => 'required',
        ];
        $customMessages = [
            'nombre.required' => 'Cuidado!! el campo del nombre no se admite vacío',
            'descripcion.required' => 'Cuidado!! el campo del descripcion no se admite vacío',
        ];

        $validatedData = $request->validate($rules, $customMessages);

        Libro::create(['nombre' => $request->nombre, 'descripcion' => $request->descripcion]);

        if ($request->portada != "") {
            $foto = $request->file('portada');
            $nombre = $foto->getClientOriginalName();
            $nombre = round(microtime(true)) . $nombre;
            $foto->move("libroImagenes", $nombre);
            $ultimo_id = Libro::all()->last();
            Libro::where('id', $ultimo_id->id)
                ->update(['portada' => "libroImagenes/" . $nombre]);
        }
        return response()->json(["success" => "Registro guardado Correctamente"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $libro = Libro::find($id);
        return  response()->json(["libro" => $libro]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //$req = $request->all();
        $rules = [
            'nombre' => 'required',
            'descripcion' => 'required',
        ];
        $customMessages = [
            'nombre.required' => 'Cuidado!! el campo del nombre no se admite vacío',
            'descripcion.required' => 'Cuidado!! el campo del descripcion no se admite vacío',
        ];

        $validatedData = $request->validate($rules, $customMessages);
        $libro_editar =  Libro::find($id);

        $libro_editar->update(['nombre' => $request->nombre, 'descripcion' => $request->descripcion]);

        if ($request->portada != null) {
            if ($libro_editar->portada != null) {
                unlink($libro_editar->portada);
            }

            $foto = $request->file('portada');
            $nombre = $foto->getClientOriginalName();
            $nombre = round(microtime(true)) . $nombre;
            $foto->move("libroImagenes", $nombre);

            Libro::where('id', $libro_editar->id)
                ->update(['portada' => "libroImagenes/" . $nombre]);
        }
        return response()->json(["success" => "Registro Actualizado Correctamente"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $libro = Libro::find($id);
        $portada =  $libro->portada;

        if ($libro->portada != "") {
            unlink($libro->portada);
        }
        $libro->delete();
        return response()->json(["success" => "Registro Eliminado Correctamente"]);
    }
}
