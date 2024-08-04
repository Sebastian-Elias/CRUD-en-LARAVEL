<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmpleadoController extends Controller
{
    public function index(){
        //Consulta toda la información a partir del modelo y muestra los 5 primeros registros, y eso lo almacena en la variable $datos que se llama empleados
        $datos['empleados']=Empleado::paginate(5);
        return view('empleado.index', $datos);
    }

    public function create(){
        return view('empleado.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request){

        //Validación de input
        $campos=[
            'Nombre'=>'required|string|max:100',
            'ApellidoPaterno'=>'required|string|max:100',
            'ApellidoMaterno'=>'required|string|max:100',
            'Correo'=>'required|email',
            'Foto'=>'required|max:10000|mimes:jpeg,png,jpg',
        ];
        $mensaje=[
            'required'=>'El :attribute es requerido',
            'Foto.required'=>'La foto es requerida'
        ];
        $this->validate($request,$campos,$mensaje);


        // Obtener todos los datos del formulario excepto el token CSRF
        $datosEmpleado = $request->except('_token');

        //cambio de formato de imagen adjunta (de temp a jpg)
        if($request->hasFile('Foto')){
            $datosEmpleado['Foto']=$request->file('Foto')->store('uploads','public');
        }
        // Insertar los datos en la base de datos
        Empleado::create($datosEmpleado);

        // Devolver los datos en formato JSON
        //return response()->json($datosEmpleado);
        return redirect('empleado')->with('mensaje','Empleado agregado') ;   }


        /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Empleado $empleado
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        //
        $empleado=Empleado::findOrFail($id);
        return view('empleado.edit', compact('empleado'));

    }



            /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Http\Empleado $empleado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){

        //Validación de input
        $campos=[
            'Nombre'=>'required|string|max:100',
            'ApellidoPaterno'=>'required|string|max:100',
            'ApellidoMaterno'=>'required|string|max:100',
            'Correo'=>'required|email',
            
        ];
        $mensaje=[
            'required'=>'El :attribute es requerido',
        ];

        if ($request->hasFile('Foto')) {
            $campos = ['Foto' => 'required|max:10000|mimes:jpeg,png,jpg'];
            $mensaje = ['Foto.required' => 'La foto es requerida'];
        }

        $this->validate($request,$campos,$mensaje);


        //
        // Obtener todos los datos del formulario excepto el token CSRF
        $datosEmpleado = $request->except(['_token','_method']);    

        //cambio de formato de imagen adjunta (de temp a jpg)
        if($request->hasFile('Foto')){
            $empleado=Empleado::findOrFail($id);
            Storage::delete('public/'.$empleado->Foto);
            $datosEmpleado['Foto']=$request->file('Foto')->store('uploads','public');
        }        

        Empleado::where('id','=',$id)->update($datosEmpleado);

        $empleado=Empleado::findOrFail($id);
        //return view('empleado.edit', compact('empleado'));

        return redirect('empleado')->with('mensaje','Empleado actualizado');
        
    }



            /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Empleado $empleado
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        //
        $empleado=Empleado::findOrFail($id);

        if(Storage::delete('public/'.$empleado->Foto)){
            Empleado::destroy($id);
        }

        
        return redirect('empleado')->with('mensaje','Empleado borrado');
    }
}


