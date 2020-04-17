<?php

namespace App\Http\Controllers\usuario\super;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App;
use Auth;

class Categoria extends Controller
{
    public function index(){
        //mostrar las categorias del colegio
        $usuario = App\User::findOrFail(Auth::user()->id);

        //consultamos el colegio
        $colegio = App\Colegio_m::where('id_superadministrador','=',$usuario->id)->first();

        //consultamos las categorias del colegio
        $categorias = App\Categoria_d::where([
            'id_colegio' => $colegio->id_colegio,
            'estado'=> 1
        ])->orderBy('c_nivel_academico','ASC')->orderBy('c_nombre','ASC')->get();

        //consultamos las secciones del colegio
        //obtenemos el grado de esos colegios
        $grados = App\Grado_m::where([
            'id_colegio' => $colegio->id_colegio,
            'estado'=> 1
        ])->orderBy('c_nivel_academico','ASC')->orderBy('c_nombre','ASC')->get();

        //prueba
        //Obteniendo todas las secciones
        $secciones = App\Seccion_d::where('estado','=',1)->orderBy('c_nombre','ASC')->get();

        return view('categoriassuper',compact('categorias','secciones','grados'));
    }

    public function agregar(Request $request){
        $request->validate([
            'nombre' => 'required',
            'nivel_academico' => 'required'
        ]);

        //ti todo esta bien
        $usuario = App\User::findOrFail(Auth::user()->id);
        //consultamos el colegio
        $colegio = App\Colegio_m::where('id_superadministrador','=',$usuario->id)->first();

        //registramos la categoria
        $categoria = new App\Categoria_d;
        $categoria->id_colegio = $colegio->id_colegio;
        $categoria->c_nombre = $request->input('nombre');
        $categoria->c_nivel_academico = $request->input('nivel_academico');
        $categoria->creador = Auth::user()->id;
        $categoria->save();

        $secciones = $request->input('optgroups');

        if(!is_null($secciones) && !empty($secciones)){
            for($i=0; $i<count($secciones); $i++){
                DB::table('seccion_categoria_p')->insert([
                    ['id_seccion' => $secciones[$i], 'id_categoria' => $categoria->id_categoria,'creador' => Auth::user()->id]
                ]);
            }
        }

        return redirect('super/categorias');
    }

    public function actualizar(Request $request){
        $request->validate([
            'actnombre' => 'required',
            'actnivel_academico' => 'required'
        ]);

        //si todo esta bien
        $categoria = App\Categoria_d::findOrFail($request->input('id_categoria'));
        $categoria->c_nombre = $request->input('actnombre');
        $categoria->c_nivel_academico = $request->input('actnivel_academico');
        $categoria->modificador = Auth::user()->id;
        $categoria->save();

        return redirect('super/categorias');
    }

    public function aplicar(Request $request){
        $categoria  = App\Categoria_d::findOrFail($request->input('id_categoria'));
        return response()->json($categoria);
    }

    public function eliminar(Request $request){
        $categoria = App\Categoria_d::findOrFail($request->input('id_categoria'));
        $categoria->estado = 0;
        $categoria->save();

        $datos = array(
            'correcto' => TRUE
        );
        return response()->json($datos);
    }

    public function quitar_categoria(Request $request){
        DB::table('seccion_categoria_p')->where([
            'id_seccion' => $request->input('id_seccion'),
            'id_categoria' => $request->input('id_categoria')
        ])->delete();

        $datos = array(
            'correcto' => TRUE
        );

        return response()->json($datos);
    }
}
