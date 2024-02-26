<?php

namespace App\Http\Controllers;

use App\Http\Requests\AlumnoFormRequest;
use App\Http\Resources\AlumnoCollection;
use App\Http\Resources\AlumnoResource;
use App\Models\Alumno;



class AlumnoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

        $alumnos = Alumno::all();
//        return response()->json($alumnos);
        //devolvemos una coleccion
        return new AlumnoCollection($alumnos);
    }

    /**
     * Store a newly created resource in storage.
     */

    //cambiamos el reques a alumnoFormRequest
    public function store(AlumnoFormRequest $request)
    {
        $alumno = new Alumno($request->input());

        $alumno->save();
//
    }
    /**
     * Display the specified resource.
     */
    public function show(int $id)

    {
        //envia un error si no existe el elemento

        $resource = Alumno::find($id);

        if (!$resource) {
            return response()->json([
                'errors' => [
                    [
                        'status' => '404',
                        'title' => 'Resource Not Found',
                        'detail' => 'The requested resource does not exist or could not be found.'
                    ]
                ]
            ], 404);
        }
        //añadimos esta linea para devolver solo un elemento solicitado
        return new AlumnoResource($resource);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AlumnoFormRequest $request, Alumno $alumno)



    {
        $alumno->update($request->input("data.attributes"));
        $alumno->save();
        return new AlumnoResource($alumno);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Alumno $alumno)
    {
        //

        $alumno->delete();
        return response()->json(null, 204);
    }
}
