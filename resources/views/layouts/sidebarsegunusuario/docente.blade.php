<head>
    <link rel="stylesheet" href="{{asset('assets/styles/css/style-panel-docente.css')}}">
</head>

<div class="side-content-wrap">
    <div class="sidebar-left open rtl-ps-none" data-perfect-scrollbar data-suppress-scroll-x="true">
        <!-- INICIO DE NUEVO MENU SLIDER -->
        <ul class="menu-lateral ">
            <li class="menu-lateral-item {{ request()->is('datatables/*') ? 'item-activo' : '' }}">
                <a class="menu-lateral-item-link" href="">
                    <i class="nav-icon i-Optimization"></i>
                    <br>
                    <span>Tablero</span>
                </a>
            </li>
            <li class="menu-lateral-item {{ request()->is('docente/docente*') ? 'item-activo' : '' }}">
                <a class="menu-lateral-item-link" href="{{route('docente/docente')}}">
                    <i class="nav-icon i-Geek"></i>
                    <br>
                    <span>Docentes</span>
                </a>
            </li>
            <li class="menu-lateral-item {{ request()->is('docente/alumno*') ? 'item-activo' : '' }}">
                <a class="menu-lateral-item-link" href="{{route('docente/alumno')}}">
                    <i class="nav-icon i-Student-Hat-2"></i>
                    <br>
                    <span>Mis alumnos</span>
                </a>
            </li>
            <li class="menu-lateral-item {{ request()->is('docente/asignartareas*') ? 'item-activo' : '' }}">
                <a class="menu-lateral-item-link" href="{{route('docente/asignartareas')}}">
                    <i class="nav-icon i-Notepad"></i>
                    <br>
                    <span>Asignación de tareas</span>
                </a>
            </li>
            <li class="menu-lateral-item {{ request()->is('docente/estadotareas*') ? 'item-activo' : '' }}">
                <a class="menu-lateral-item-link" href="{{route('docente/estadotareas')}}">
                    <i class="nav-icon i-Folder-With-Document"></i>
                    <br>
                    <span>Estado de tareas</span>
                </a>
            </li>
            <li class="menu-lateral-item {{ request()->is('docente/videoclase*') ? 'item-activo' : '' }}">
                <a class="menu-lateral-item-link" href="{{route('docente/videoclase')}}">
                    <i class="nav-icon i-Movie"></i>
                    <br>
                    <span>Videoclase</span>
                </a>
            </li>
        </ul>
        <!-- FIN DE NUEVO MENU SLIDER -->
    </div>
    <div class="sidebar-overlay"></div>
</div>