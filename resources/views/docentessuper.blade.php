@extends('reutilizable.principal')
@section('page-css')
<link rel="stylesheet" href="{{asset('assets/styles/vendor/ladda-themeless.min.css')}}">
<link rel="stylesheet" href="{{ asset('assets/styles/vendor/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/styles/css/libreria/slim/slimselect.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/smart.wizard/smart_wizard.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/smart.wizard/smart_wizard_theme_arrows.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/sweetalert2.min.css')}}">

<link rel="stylesheet" href="{{asset('assets/styles/css/style-super.css')}}">
@endsection

@section('main-content')

<h2 class="hs_titulo">Docentes</h2>

<div class="row hs_contenedor">
    <div class="card">
        <div class="card-body">
            <div class="hs_encabezado">
                <h4 class="hs_encabezado-titulo">Docentes de la Institución</h4>
                <div class="hs_encabezado-linea"></div>
            </div>
            
            <div class="hs_dos-botones">
                <div class="hs_barra-filtrado">
                    <strong>Filtrar por:</strong>
                    <select name="" id="" class="form-control">
                        <option value="">Primero</option>
                        <option value="">Segundo</option>
                        <option value="">Tercero</option>
                        <option value="">Cuarto</option>
                        <option value="">Quinto</option>
                    </select>
                    <select name="" id="" class="form-control">
                        <option value="">A</option>
                        <option value="">B</option>
                        <option value="">C</option>
                        <option value="">D</option>
                        <option value="">E</option>
                    </select>
                </div>

                <button type="button" data-toggle="modal" data-target=".bd-example-modal-lg" class="btn btn-primary"><i class="i-Add-User text-white mr-2"></i>Nuevo docente</button>
            </div>

            <div class="table-responsive">
                <table id="ul-contact-list" class="hs_tabla display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>DNI</th>
                            <th>Nombre</th>
                            <th>Nacionalidad</th>
                            <th>Fecha Nacimiento</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($docentes as $docente)
                            <tr>
                                <td>{{$docente->c_dni}}</td>
                                <td>
                                <a href="{{url('super/docente/'.$docente->id_docente)}}">
                                        <div class="ul-widget-app__profile-pic">
                                            @if(is_null($docente->c_foto)  || empty($docente->c_foto))
                                                @if(strtoupper($docente->c_sexo)=='M')
                                                    <img class="profile-picture avatar-sm mb-2 rounded-circle img-fluid" src="{{asset('assets/images/usuario/teacherman.png')}}" alt="fotografía" style="height: 25px; width: 25px;">
                                                @else
                                                    <img class="profile-picture avatar-sm mb-2 rounded-circle img-fluid" src="{{asset('assets/images/usuario/teacherwoman.png')}}" alt="fotografía" style="height: 25px; width: 25px;">
                                                @endif

                                            @else
                                                <img class="profile-picture avatar-sm mb-2 rounded-circle img-fluid" src="{{url('super/docente/foto/'.$docente->c_foto)}}" alt="Foto del docente">
                                            @endif
                                            {{$docente->c_nombre}}
                                        </div>
                                    </a>
                                </td>
                                <td>{{$docente->c_nacionalidad}}</td>
                                <td>{{$docente->t_fecha_nacimiento}}</td>
                                <td><a href="mailto:{{$docente->c_correo}}">{{$docente->c_correo}}</a></td>
                                <td><span class="text-info">{{$docente->c_telefono}}</span></td>
                                <td>
                                    <a href="{{url('super/docente/'.$docente->id_docente)}}" class="badge badge-warning m-2" data-toggle="tooltip" data-placement="top" title="Editar">
                                        <i class="nav-icon i-Pen-4"></i>
                                    </a>
                                    <a id="btnEliminarDocente{{$docente->id_docente}}" onclick="fxConfirmacionEliminarDocente({{$docente->id_docente}});" data-toggle="tooltip" data-placement="top" title="Eliminar" href="#" class="badge badge-danger m-2">X</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                   
                </table>
            </div>
        </div>
    </div>
</div>

<!-- begin::modal -->
<div class="ul-card-list__modal">
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Nuevo docente</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">


                            <!-- SmartWizard html -->
                        <form action="{{route('super/docente/agregar')}}" id="myForm" role="form" data-toggle="validator" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                            @csrf
                                <div id="smartwizard">
                                    <ul>
                                        <li><a href="#step-1">Paso 1<br /><small>Datos del docente</small></a></li>
                                        <li><a href="#step-2">Paso 2<br /><small>Dará clases en</small></a></li>
                                        <li><a href="#step-3">Paso 3<br /><small>Foto del docente</small></a></li>
                                        <li><a href="#step-4">Paso 4<br /><small>Datos de acceso</small></a></li>
                                    </ul>
                                    <div>
                                        <div id="step-1">
                                            <div id="form-step-0" role="form" data-toggle="validator">
                                                
                                                    
                                                        <div class="form-group">
                                                            <label for="dni" >DNI</label>
                                                            <input type="text" class="form-control form-control-sm" id="dni" name="dni" minlength="8" maxlength="8" required>
                                                            <div class="help-block with-errors text-danger"></div>
                                                        </div>  
                                                    
                                                    
                                                        <div class="form-group">
                                                            <label for="nombre" >Nombre</label>
                                                            <input type="text" class="form-control form-control-sm" id="nombre" name="nombre" required>
                                                            <div class="help-block with-errors text-danger"></div>
                                                        </div>     
                                                    


                                                    
                                                        <div class="form-group">
                                                            <label for="nacionalidad" >Nacionalidad</label>
                                                            <input type="text" class="form-control form-control-sm" id="nacionalidad" name="nacionalidad" value="Peruano" required>
                                                            <div class="help-block with-errors text-danger"></div>
                                                        </div>
                                                        
                                                        <div class="form-group">
                                                            <label for="sexo">Sexo</label>
                                                            <select name="sexo" id="sexo" class="form-control form-control-sm">
                                                                <option value="M">Masculino</option>
                                                                <option value="F" selected>Femenino</option>
                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="especialidad">Especialidad</label>
                                                            <input type="text" class="form-control form-control-sm" id="especialidad" name="especialidad" placeholder="Ejm: Ciencia y Tecnología">
                                                            <div class="help-block with-errors text-danger"></div>
                                                        </div>
                                                    
                                                    
                                                        <div class="form-group">
                                                            <label for="fecha_nacimiento">F.Nacimiento</label>
                                                            <input type="date" class="form-control form-control-sm" id="fecha_nacimiento" name="fecha_nacimiento" required>
                                                            <div class="help-block with-errors text-danger"></div>
                                                        </div>
                                                
                                                    
                                                        <div class="form-group">
                                                            <label for="correo">Correo</label>
                                                            <input type="email" class="form-control form-control-sm" id="correo" name="correo" required>
                                                            <div class="help-block with-errors text-danger"></div>
                                                        </div>
                                                    

                                                    
                                                        <div class="form-group">
                                                            <label for="telefono">Teléfono</label>
                                                            <input type="text" class="form-control form-control-sm" id="telefono" name="telefono" required>
                                                            <div class="help-block with-errors text-danger"></div>
                                                        </div>
                                                
                                                    
                                                        <div class="form-group">
                                                            <label for="direccion">Dirección</label>
                                                            <input type="text" class="form-control form-control-sm" id="direccion" name="direccion" required>
                                                            <div class="help-block with-errors text-danger"></div>
                                                        </div>

                                                
                                            </div>
                        
                                        </div>
                                        <div id="step-2">
                                            <div class="form-group">
                                                <select id="optsecciones" name="optsecciones[]" multiple>
                                                    @foreach($grados as $grado)
                                                        <optgroup label="{{$grado->c_nombre}} - {{$grado->c_nivel_academico}}">
                                                            @foreach($grado->secciones->where('estado','=',1) as $seccion)
                                                                <option value="{{$seccion->id_seccion}}">{{$seccion->c_nombre}}</option>
                                                            @endforeach
                                                        </optgroup>
                                                    @endforeach
                                                </select>                
                                            </div>
                                        </div>
                                        <div id="step-3">
                                            <div class="form-group">
                                                <input type='file' class="form-control form-control-lg" id="fotodocente" name="fotodocente">
                                            </div>                                                

                                        </div>
                                        <div id="step-4" class="">                                                
                                                <div class="form-group">
                                                    <label for="usuariodocente">Usuario</label>
                                                    <input type="text" class="form-control" id="usuariodocente" name="usuariodocente" required>
                                                </div>                            

                                                <div class="form-group">
                                                    <label for="contrasenadocente">Contraseña</label>
                                                    <input type="text" class="form-control" id="contrasenadocente" name="contrasenadocente" value="12345678" readonly>
                                                </div>
                                            
                                        </div>

                                    </div>
                                </div>
                            </form>
                            


                </div>

                <!--<div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>-->
            </div>
        </div>
    </div>
</div>
<!-- end::modal -->



@endsection

@section('page-js')

<!-- 
<script src="{{ asset('assets/js/vendor/datatables.min.js') }}"></script>
-->

<!-- page script -->
<script src="{{ asset('assets/js/tooltip.script.js') }}"></script>
<script src="{{asset('assets/js/vendor/sweetalert2.min.js')}}"></script>
<script src="{{asset('assets/js/libreria/slim/slimselect.min.js')}}"></script>
<script src="{{asset('assets/js/libreria/validator/validator.min.js')}}"></script>
<script src="{{asset('assets/js/vendor/jquery.smartWizard.min.js')}}"></script>

<script src="{{asset('assets/js/vendor/spin.min.js')}}"></script>
<script src="{{asset('assets/js/vendor/ladda.js')}}"></script>
<script src="{{asset('assets/js/superadmin/docentes.js')}}"></script>

<script>
$('#ul-contact-list').DataTable();
new SlimSelect({
            select: '#optsecciones',
            placeholder: 'Elige secciones'
          });
</script>
@endsection