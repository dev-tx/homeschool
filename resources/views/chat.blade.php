<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat Home school</title>

    <!-- Favicon -->
    <link rel="icon" href="{{asset('assets/chat/dist/media/img/favicon.png')}}" type="image/png">

    <!-- Soho css -->
    <link rel="stylesheet" href="{{asset('assets/chat/dist/css/soho.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/styles/css/libreria/slim/slimselect.min.css')}}">
    <script src="{{asset('js/chat.js')}}" defer></script>
        <script>
            window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
            ]); ?>
        </script>
        <script>
            window.Laravel.userId = <?php echo Auth::user()->id; ?>
        </script>
</head>
<body class="dark">
    
<!-- new group modal -->
<div class="modal" id="newGroup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa fa-users"></i> Nuevo grupo
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="ti-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="group_name" class="col-form-label">Nombre de grupo</label>
                        <input type="text" class="form-control" id="group_name">
                    </div>
                    <div class="form-group">
                        <label for="optusuarios">Usuarios</label>
                        <select id="optusuarios" multiple>
                            @foreach($usuarios as $usuario)
                                    @php
                                        $nombre = '';
                                        if(is_null($usuario->id_docente) && is_null($usuario->id_alumno) && $usuario->b_root==0){
                                            $nombre = (App\Colegio_m::where('id_superadministrador','=',$usuario->id)->first())->c_representante_legal;
                                        }else if(!is_null($usuario->id_docente)){
                                            $nombre = (App\Docente_d::findOrFail($usuario->id_docente))->c_nombre;
                                        }else if(!is_null($usuario->id_alumno)){
                                            $nombre = (App\Alumno_d::findOrFail($usuario->id_alumno))->c_nombre;
                                        }
                                    @endphp
                                    <option value="{{$usuario->id}}">{{$nombre}}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btnCrearGrupo">Crear grupo</button>
            </div>
        </div>
    </div>
</div>
<!-- ./ new group modal -->



<div class="layout" id="chat">

    
    <nav class="navigation">
        <div class="nav-group">
            <ul>
                <li>
                    <a class="logo" href="#">
                        <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                             width="33.004px" height="33.003px" viewBox="0 0 33.004 33.003" style="enable-background:new 0 0 33.004 33.003;"
                             xml:space="preserve">
                            <g>
                                <path d="M4.393,4.788c-5.857,5.857-5.858,15.354,0,21.213c4.875,4.875,12.271,5.688,17.994,2.447l10.617,4.161l-4.857-9.998
                                    c3.133-5.697,2.289-12.996-2.539-17.824C19.748-1.072,10.25-1.07,4.393,4.788z M25.317,22.149l0.261,0.512l1.092,2.142l0.006,0.01
                                    l1.717,3.536l-3.748-1.47l-0.037-0.015l-2.352-0.883l-0.582-0.219c-4.773,3.076-11.221,2.526-15.394-1.646
                                    C1.469,19.305,1.469,11.481,6.277,6.672c4.81-4.809,12.634-4.809,17.443,0.001C27.919,10.872,28.451,17.368,25.317,22.149z"/>
                                <g>
                                    <circle cx="9.835" cy="16.043" r="1.833"/>
                                    <circle cx="15.502" cy="16.043" r="1.833"/>
                                    <circle cx="21.168" cy="16.043" r="1.833"/>
                                </g>
                            </g>
                            <g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g>
                        </svg>
                    </a>
                </li>
                <li>
                    <a data-navigation-target="chats" class="active" href="#">
                        <i class="ti-comment-alt"></i>
                    </a>
                </li>
                <li>
                    <a data-navigation-target="friends" href="#" class="notifiy_badge">
                        <i class="ti-user"></i>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    
    <chat-app :user="{{Auth::user()}}"><chat-app>
   
</div>

<script src="{{asset('assets/chat/vendor/jquery-3.4.1.min.js')}}"></script>
<script src="{{asset('assets/chat/vendor/popper.min.js')}}"></script>
<script src="{{asset('assets/chat/vendor/bootstrap/bootstrap.min.js')}}"></script>
<script src="{{asset('assets/chat/dist/js/soho.min.js')}}"></script>
<script src="{{asset('assets/js/libreria/slim/slimselect.min.js')}}"></script>
<script src="{{asset('assets/chat/dist/js/examples.js')}}"></script>
</body>
</html>