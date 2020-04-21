<?php

namespace App\Http\Controllers\usuario\alumno;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App;
use Auth;

class Tarea extends Controller
{
    public function index()
    {
        $usuarioAlumno = App\User::findOrFail(Auth::user()->id);
        $alumno = App\Alumno_d::where([
            'id_alumno' => $usuarioAlumno->id_alumno,
            'estado' => 1
        ])->first();

        if (!is_null($alumno) && !empty($alumno)) {
            //obteniendo las tareas pendientes
            $tareas_del_alumno = $alumno->tareas_asignados()->where('tarea_d.estado', '=', 1)->orderBy('tarea_d.created_at', 'DESC')->get();
            return view('tareasalumno', compact('tareas_del_alumno'));
        } else {
            return redirect('home');
        }
    }

    public function listar(Request $request)
    {

        $usuarioAlumno = App\User::findOrFail(Auth::user()->id);
        $alumno = App\Alumno_d::where([
            'id_alumno' => $usuarioAlumno->id_alumno,
            'estado' => 1
        ])->first();

        if (!is_null($alumno) && !empty($alumno)) {
            //obteniendo las tareas pendientes
            $tareas_del_alumno = $alumno->tareas_asignados()->where('tarea_d.estado', '=', 1)->get();
            $events = array();
            $i = 0;
            foreach ($tareas_del_alumno as $tarea) {
                $color = '';
                if ($tarea->pivot->c_estado == 'APEN' && $tarea->t_fecha_hora_entrega > date('Y-m-d H:i:s')) {
                    $color = '#FFC107';
                } else if ($tarea->pivot->c_estado == 'APEN' && $tarea->t_fecha_hora_entrega <= date('Y-m-d H:i:s')) {
                    $color = 'red';
                } else if ($tarea->pivot->c_estado == 'AENV') {
                    $color = '#003473';
                } else if ($tarea->pivot->c_estado == 'ACAL') {
                    $color = '#4CAF50';
                }
                $events[$i] = array(
                    'title' => $tarea->c_titulo,
                    'start' => $tarea->created_at,
                    'end' => $tarea->t_fecha_hora_entrega,
                    'color' => $color
                );
                $i++;
            }
            return response()->json($events);
        }
    }

    public function info_pendiente($id_tarea)
    {
        $usuarioAlumno = App\User::findOrFail(Auth::user()->id);
        $alumno = App\Alumno_d::where([
            'id_alumno' => $usuarioAlumno->id_alumno,
            'estado' => 1
        ])->first();

        if (!is_null($alumno) && !empty($alumno)) {
            $tarea = App\Tarea_d::where([
                'id_tarea' => $id_tarea,
                'estado' => 1
            ])->first();
            //comprobamos si la tarea pertenece al alumno
            $alumno_de_tarea = $tarea->alumnos_asignados()->where([
                'alumno_d.estado' => 1,
                'alumno_tarea_respuesta_p.c_estado' => 'APEN',
                'alumno_d.id_alumno' => $alumno->id_alumno
            ])->first();

            if (!is_null($alumno_de_tarea) && !empty($alumno_de_tarea)) {
                return view('alumno.infotareapendiente', compact('tarea'));
            } else {
                return redirect('alumno/tareas');;
            }
        } else {
            return redirect('alumno/tareas');;
        }
    }

    public function info_enviado($id_tarea)
    {
        $usuarioAlumno = App\User::findOrFail(Auth::user()->id);
        $alumno = App\Alumno_d::where([
            'id_alumno' => $usuarioAlumno->id_alumno,
            'estado' => 1
        ])->first();

        if (!is_null($alumno) && !empty($alumno)) {
            $tarea = App\Tarea_d::where([
                'id_tarea' => $id_tarea,
                'estado' => 1
            ])->first();
            //comprobamos si la tarea pertenece al alumno
            $alumno_de_tarea = $tarea->alumnos_asignados()->where([
                'alumno_d.estado' => 1,
                'alumno_tarea_respuesta_p.c_estado' => 'AENV',
                'alumno_d.id_alumno' => $alumno->id_alumno
            ])->first();

            if (!is_null($alumno_de_tarea) && !empty($alumno_de_tarea)) {
                return view('alumno.infotareapendiente', compact('tarea'));
            } else {
                return redirect('alumno/tareas');;
            }
        } else {
            return redirect('alumno/tareas');;
        }
    }

    public function responder(Request $request)
    {
        $usuarioAlumno = App\User::findOrFail(Auth::user()->id);
        $alumno = App\Alumno_d::where([
            'id_alumno' => $usuarioAlumno->id_alumno,
            'estado' => 1
        ])->first();

        $tarea = App\Tarea_d::where([
            'id_tarea' => $request->input('preid_tarea'),
            'estado' => 1
        ])->first();
        //comprobamos si la tarea pertenece al alumno
        $alumno_de_tarea = $tarea->alumnos_asignados()->where([
            'alumno_d.estado' => 1,
            'alumno_tarea_respuesta_p.c_estado' => 'APEN',
            'alumno_d.id_alumno' => $alumno->id_alumno
        ])->first();

        if (!is_null($alumno_de_tarea) && !empty($alumno_de_tarea)) {

            //creamos la respuesta
            $respuesta = new App\Respuesta_d;
            $respuesta->c_observacion = $request->input('preobservacion');
            $respuesta->creador = $usuarioAlumno->id;
            $respuesta->save();

            //subida de archivo,si es que existe
            $archivo = $request->file('prearchivo');

            if (!is_null($archivo) && !empty($archivo)) {
                $nombre = $archivo->getClientOriginalName();
                $archivo->storeAs('tarearespuesta/' . $tarea->id_tarea . '/' . $respuesta->id_respuesta . '/', $nombre);
                $respuesta->c_url_archivo = $nombre;
                $respuesta->save();
            }

            DB::table('alumno_tarea_respuesta_p')
                ->where([
                    'id_tarea' => $tarea->id_tarea,
                    'id_alumno' => $alumno->id_alumno,
                ])
                ->update([
                    'id_respuesta' => $respuesta->id_respuesta,
                    'c_estado' => 'AENV'
                ]);

            return redirect('alumno/tareapendiente/' . $tarea->id_tarea);
        } else {
            return redirect('home');
        }
    }

    public function comentar_pendiente(Request $request)
    {

        $usuarioAlumno = App\User::findOrFail(Auth::user()->id);
        $alumno = App\Alumno_d::where([
            'id_alumno' => $usuarioAlumno->id_alumno,
            'estado' => 1
        ])->first();

        if (!is_null($alumno) && !empty($alumno)) {
            //aplicando la tarea
            $tarea = App\Tarea_d::where([
                'id_tarea' => $request->input('id_tarea'),
                'estado' => 1
            ])->first();

            //comprobamos si la tarea pertenece al alumno
            $alumno_de_tarea = $tarea->alumnos_asignados()->where([
                'alumno_d.estado' => 1,
                'alumno_tarea_respuesta_p.c_estado' => 'APEN',
                'alumno_d.id_alumno' => $alumno->id_alumno
            ])->first();

            if (!is_null($alumno_de_tarea) && !empty($alumno_de_tarea)) {
                //agregamos el comentario
                $comentario = new App\Comentario_d;
                $comentario->id_tarea = $tarea->id_tarea;
                $comentario->id_usuario = $usuarioAlumno->id;
                $comentario->c_descripcion = $request->input('comentario');
                $id_referencia =  $request->input('id_comentario_referncia');
                if (!is_null($id_referencia) && !empty($id_referencia)) {
                    $ref_com = App\Comentario_d::findOrFail($id_referencia);
                    $comentario->id_comentario_referencia = $ref_com->id_comentario;
                }
                $comentario->creador = $usuarioAlumno->id;
                $comentario->save();
            }
        }
        return redirect('alumno/tareapendiente/' . $request->input('id_tarea'));
    }

    public function descargar_archivo($id_tarea, $id_respuesta)
    {
        $tarea = App\Tarea_d::findOrFail($id_tarea);
        $respuesta = App\Respuesta_d::findOrFail($id_respuesta);
        return Storage::download('tarearespuesta/' . $tarea->id_tarea . '/' . $respuesta->id_respuesta . '/' . $respuesta->c_url_archivo);
    }
}
