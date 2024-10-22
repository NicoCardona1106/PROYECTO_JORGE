<body class="hold-transition sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Inicio</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="adm_contactos.php" class="nav-link">Soporte</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" data-slide="true" href="../controlador/logout.php" role="button">
          <i class="fas fa-sign-out-alt"></i> Salir
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
      <span class="brand-text font-weight-light">Portal de Proveedores</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image"> 
          <img src="../assets/img/LOGO.png" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">Bienvenido, Proveedor</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Gestión de Productos -->
          <li class="nav-item">
            <a href="MisProductos.php" class="nav-link">
              <i class="nav-icon fas fa-boxes"></i>
              <p>Mis Productos</p>
            </a>
          </li>
          <!-- Gestión de Órdenes -->
          <li class="nav-item">
            <a href="ordenes.php" class="nav-link">
              <i class="nav-icon fas fa-file-invoice"></i>
              <p>Órdenes Recibidas</p>
            </a>
          </li>
          <!-- Historial de Ventas -->
          <li class="nav-item">
            <a href="historial_ventas.php" class="nav-link">
              <i class="nav-icon fas fa-history"></i>
              <p>Historial de Ventas</p>
            </a>
          </li>
          <!-- Soporte -->
          <li class="nav-item">
            <a href="adm_contactos.php" class="nav-link">
              <i class="nav-icon fas fa-headset"></i>
              <p>Soporte</p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
