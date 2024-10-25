<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="login.html">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">{{ session('razonsocialempresa') }}</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('menu') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>{{ session('nombre_usuario') }}</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Interface
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li
        class="nav-item {{ Route::is('productos.mantenimiento', 'almacen.mantenimiento', 'productos.transportista') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true"
            aria-controls="collapseTwo">
            <i class="fas fa-fw fa-archive"></i>
            <span>Archivo</span>
        </a>
        <div id="collapseTwo"
            class="collapse {{ Route::is('productos.mantenimiento', 'almacen.mantenimiento', 'movimiento.mantenimiento', 'transportista.mantenimiento', 'vendedor.mantenimiento', 'condicion.mantenimiento', 'concepto.mantenimiento', 'clientes.mantenimiento', 'proveedor.mantenimiento', 'documentos.mantenimiento', 'usuarios.mantenimiento', 'tipocambio.mantenimiento') ? 'show' : '' }}"
            aria-labelledby="headingproductos" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" data-toggle="collapse" href="#collapsep" role="button" aria-expanded="false"
                    aria-controls="collapsep" style="color: #1477d2">
                    <span>Productos</span>
                </a>
                <div class="collapse" id="collapsep">
                    <a class="collapse-item" href="{{ route('productos.mantenimiento') }}"
                        style="{{ Route::is('productos.mantenimiento') ? 'font-weight: bold; color: #000;' : 'color: #1477d2;' }}">
                        <span>Mantenimiento</span>
                    </a>
                </div>
                <a class="collapse-item" data-toggle="collapse" href="#collapsea" role="button" aria-expanded="false"
                    aria-controls="collapsea" style="color: #1477d2">
                    <span>Almacen</span>
                </a>
                <div class="collapse" id="collapsea">
                    <a class="collapse-item" href="{{ route('almacen.mantenimiento') }}"
                        style="{{ Route::is('almacen.mantenimiento') ? 'font-weight: bold; color: #000;' : 'color: #1477d2;' }}">
                        <span>Almacen</span>
                    </a>
                    <a class="collapse-item" href="{{ route('movimiento.mantenimiento') }}"
                        style="{{ Route::is('movimiento.mantenimiento') ? 'font-weight: bold; color: #000;' : 'color: #1477d2;' }}">
                        <span>Movimientos</span>
                    </a>
                    <a class="collapse-item" href="{{ route('transportista.mantenimiento') }}"
                        style="{{ Route::is('transportista.mantenimiento') ? 'font-weight: bold; color: #000;' : 'color: #1477d2;' }}">
                        <span>Transportista</span>
                    </a>
                </div>
                <!-- <a class="collapse-header" data-toggle="collapse" href="#collapsev" role="button" aria-expanded="false"
                    aria-controls="collapsev">Ventas </a>-->
                <a class="collapse-item" data-toggle="collapse" href="#collapsev" role="button" aria-expanded="false"
                    aria-controls="collapsev" style="color: #1477d2">
                    <span>Ventas</span>
                </a>
                <div class="collapse" id="collapsev">
                    <a class="collapse-item" href="{{ route('vendedor.mantenimiento') }}"
                        style="{{ Route::is('vendedor.mantenimiento') ? 'font-weight: bold; color: #000;' : 'color: #1477d2;' }}">
                        <span>Vendedores</span>
                    </a>
                    <a class="collapse-item" href="{{ route('condicion.mantenimiento') }}"
                        style="{{ Route::is('condicion.mantenimiento') ? 'font-weight: bold; color: #000;' : 'color: #1477d2;' }}">
                        <span>Condicion</span>
                    </a>
                    <a class="collapse-item" href="{{ route('concepto.mantenimiento') }}"
                        style="{{ Route::is('concepto.mantenimiento') ? 'font-weight: bold; color: #000;' : 'color: #1477d2;' }}">
                        <span>Concepto venta</span>
                    </a>
                </div>
                <a class="collapse-item" data-toggle="collapse" href="#collapsec" role="button" aria-expanded="false"
                    aria-controls="collapsec" style="color: #1477d2">
                    <span>Clientes</span>
                </a>
                <div class="collapse" id="collapsec">
                    <a class="collapse-item" href="{{ route('clientes.mantenimiento') }}"
                        style="{{ Route::is('clientes.mantenimiento') ? 'font-weight: bold; color: #000;' : 'color: #1477d2;' }}">
                        <span>Mantenimiento</span>
                    </a>
                </div>
                <a class="collapse-item" data-toggle="collapse" href="#collapsepr" role="button" aria-expanded="false"
                    aria-controls="collapsepr" style="color: #1477d2">
                    <span>Proveedores</span>
                </a>
                <div class="collapse" id="collapsepr">
                    <a class="collapse-item" href="{{ route('proveedor.mantenimiento') }}"
                        style="{{ Route::is('proveedor.mantenimiento') ? 'font-weight: bold; color: #000;' : 'color: #1477d2;' }}">
                        <span>Mantenimiento</span>
                    </a>
                </div>
                <a class="collapse-item" data-toggle="collapse" href="#collapsed" role="button" aria-expanded="false"
                    aria-controls="collapsed" style="color: #1477d2">
                    <span>Documentos</span>
                </a>
                <div class="collapse" id="collapsed">
                    <a class="collapse-item" href="{{ route('documentos.mantenimiento') }}"
                        style="{{ Route::is('documentos.mantenimiento') ? 'font-weight: bold; color: #000;' : 'color: #1477d2;' }}">
                        <span>Documentos</span>
                    </a>
                </div>
                <a class="collapse-item" data-toggle="collapse" href="#collapseu" role="button" aria-expanded="false"
                    aria-controls="collapseu" style="color: #1477d2">
                    <span>Usuarios</span>
                </a>
                <div class="collapse" id="collapseu">
                    <a class="collapse-item" href="{{ route('usuarios.mantenimiento') }}"
                        style="{{ Route::is('usuarios.mantenimiento') ? 'font-weight: bold; color: #000;' : 'color: #1477d2;' }}">
                        <span>Mantenimiento</span>
                    </a>
                </div>
                <a class="collapse-item" data-toggle="collapse" href="#collapsetc" role="button" aria-expanded="false"
                    aria-controls="collapsetc" style="color: #1477d2">
                    <span>Tipo de cambio</span>
                </a>
                <div class="collapse" id="collapsetc">
                    <a class="collapse-item" href="{{ route('tipocambio.mantenimiento') }}"
                        style="{{ Route::is('tipocambio.mantenimiento') ? 'font-weight: bold; color: #000;' : 'color: #1477d2;' }}">
                        <span>Registro</span>
                    </a>
                </div>
            </div>
        </div>
    </li>

    <!-- Nav Item - Utilities Collapse Menu -->
    <li class="nav-item {{ Route::is('notaingreso.mantenimiento') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseni" aria-expanded="true"
            aria-controls="collapseni">
            <i class="fa fa-fw fa-tasks"></i>
            <span>Almacen</span>
        </a>
        <div id="collapseni" class="collapse {{ Route::is('notaingreso.mantenimiento') ? 'show' : '' }}"
            aria-labelledby="headingPro" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="nav-link" href="{{ route('notaingreso.mantenimiento') }}"
                    style="{{ Route::is('notaingreso.mantenimiento') ? 'font-weight: bold; color: #000;' : 'color: #1477d2;' }}">
                    <span>Nota de Ingreso</span>
                </a>
            </div>
        </div>
    </li>
    <li
        class="nav-item {{ Route::is('cotizaciones.mantenimiento', 'notapedido.mantenimiento', 'guiaremision.mantenimiento', 'registrodeventas.mantenimiento') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true"
            aria-controls="collapseThree">
            <i class="fas fa-fw fa-archive"></i>
            <span>Ventas</span>
        </a>
        <div id="collapseThree"
            class="collapse {{ Route::is('cotizaciones.mantenimiento', 'notapedido.mantenimiento', 'guiaremision.mantenimiento', 'registrodeventas.mantenimiento','registroguiaremision.mantenimiento') ? 'show' : '' }}"
            aria-labelledby="headingventas" data-parent="#accordionSidebar">
            <div class="bg-white collapse-inner rounded">
                <a class="collapse-item" href="{{ route('cotizaciones.mantenimiento') }}"
                    style="{{ Route::is('cotizaciones.mantenimiento') ? 'font-weight: bold; color: #000;' : 'color: #1477d2;' }}">
                    <span>Cotizaciones</span>
                </a>
                <a class="collapse-item" href="{{ route('notapedido.mantenimiento') }}"
                    style="{{ Route::is('notapedido.mantenimiento') ? 'font-weight: bold; color: #000;' : 'color: #1477d2;' }}">
                    <span>Nota de Pedido</span>
                </a>
                <div style="border-bottom: 2px solid #ddd; margin: 5px 0;"></div>
                <a class="collapse-item" href="{{ route('guiaremision.mantenimiento') }}"
                    style="{{ Route::is('guiaremision.mantenimiento') ? 'font-weight: bold; color: #000;' : 'color: #1477d2;' }}">
                    <span>Guía de Remisión</span>
                </a>
                <a class="collapse-item" href="{{ route('comprobantes.mantenimiento') }}"
                    style="{{ Route::is('comprobantes.mantenimiento') ? 'font-weight: bold; color: #000;' : 'color: #1477d2;' }}">
                    <span>Registro de Ventas</span>
                </a>
                <a class="collapse-item" href="{{ route('notacredito.mantenimiento') }}"
                    style="{{ Route::is('notacredito.mantenimiento') ? 'font-weight: bold; color: #000;' : 'color: #1477d2;' }}">
                    <span>Nota de credito</span>
                </a>
                <a class="collapse-item" href="{{ route('notacredito.mantenimiento') }}"
                    style="{{ Route::is('notacredito.mantenimiento') ? 'font-weight: bold; color: #000;' : 'color: #1477d2;' }}">
                    <span>Nota de debito</span>
                </a>
                <div style="border-bottom: 2px solid #ddd; margin: 5px 0;"></div>
                <a class="collapse-item" href="{{ route('registroguiaremision.mantenimiento') }}"
                    style="{{ Route::is('registroguiaremision.mantenimiento') ? 'font-weight: bold; color: #000;' : 'color: #1477d2;' }}">
                    <span>Registro de GRE</span>
                </a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Addons
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true"
            aria-controls="collapsePages">
            <i class="fas fa-fw fa-folder"></i>
            <span>Pages</span>
        </a>
        <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Login Screens:</h6>
                <a class="collapse-item" href="login.html">Login</a>
                <a class="collapse-item" href="register.html">Register</a>
                <a class="collapse-item" href="forgot-password.html">Forgot Password</a>
                <div class="collapse-divider"></div>
                <h6 class="collapse-header">Other Pages:</h6>
                <a class="collapse-item" href="404.html">404 Page</a>
                <a class="collapse-item" href="blank.html">Blank Page</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Charts -->
    <li class="nav-item">
        <a class="nav-link" href="charts.html">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Charts</span></a>
    </li>

    <!-- Nav Item - Tables -->
    <li class="nav-item">
        <a class="nav-link" href="tables.html">
            <i class="fas fa-fw fa-table"></i>
            <span>Tables</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

    <!-- Sidebar Message -->
    <div class="sidebar-card d-none d-lg-flex">
        <img class="sidebar-card-illustration mb-2" src="img/undraw_rocket.svg" alt="...">
        <p class="text-center mb-2"><strong>SB Admin Pro</strong> is packed with premium features, components,
            and more!</p>
        <a class="btn btn-success btn-sm" href="https://startbootstrap.com/theme/sb-admin-pro">Upgrade to
            Pro!</a>
    </div>

</ul>
<script>

</script>