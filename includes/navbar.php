<?php
$es_publico = strpos($_SERVER['REQUEST_URI'], 'index.php') !== false || !isset($_SESSION['id_usuario']);
?>

<?php
// includes/navbar.php
if (session_status() === PHP_SESSION_NONE) session_start();
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="dashboard.php">
            <i class="fas fa-utensils me-2"></i>NicaFood ERP
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>" 
                       href="dashboard.php">
                        <i class="fas fa-chart-line me-1"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= strpos($_SERVER['PHP_SELF'], '/rrhh/') !== false ? 'active' : '' ?>" 
                       href="modules/rrhh/index.php">
                        <i class="fas fa-users me-1"></i> Recursos Humanos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>" 
                       href="index.php">
                        <i class="fas fa-home me-1"></i> Menú/Ventas
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'checkout.php' ? 'active' : '' ?>" 
                       href="checkout.php">
                        <i class="fas fa-cash-register me-1"></i> Caja
                    </a>
                </li>
            </ul>
            
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-1"></i>
                        <?= htmlspecialchars($_SESSION['nombre'] ?? 'Usuario') ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Perfil</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Configuración</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
.navbar {
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
.nav-link.active {
    background: rgba(255,255,255,0.1);
    border-radius: 5px;
}
</style>
<?php if ($es_publico): ?>
<!-- Navbar Público (Menú de pedidos) -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/nicafood/index.php">
            <i class="fas fa-utensils me-2"></i>NicaFood
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarPublico">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarPublico">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/nicafood/index.php">
                        <i class="fas fa-home me-1"></i>Inicio
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/nicafood/modules/pedidos/index.php">
                        <i class="fas fa-shopping-cart me-1"></i>Mi Pedido
                    </a>
                </li>
                <?php if (isset($_SESSION['id_usuario'])): ?>
                <li class="nav-item">
                    <a class="nav-link btn btn-outline-light btn-sm ms-2" href="/nicafood/modules/dashboard/index.php">
                        <i class="fas fa-tachometer-alt me-1"></i>Panel
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-outline-danger btn-sm ms-2" href="/nicafood/auth/logout.php">
                        <i class="fas fa-sign-out-alt me-1"></i>Salir
                    </a>
                </li>
                <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link btn btn-outline-light btn-sm ms-2" href="/nicafood/login.php">
                        <i class="fas fa-user me-1"></i>Acceso
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<?php else: ?>
<!-- Navbar Admin (Panel interno) -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="/nicafood/modules/dashboard/index.php">
            <i class="fas fa-chart-line me-2"></i>NicaFood ERP
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAdmin">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarAdmin">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'dashboard') ? 'active' : '' ?>" 
                       href="/nicafood/modules/dashboard/index.php">
                        <i class="fas fa-home me-1"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'personas') ? 'active' : '' ?>" 
                       href="/nicafood/modules/personas/index.php">
                        <i class="fas fa-users me-1"></i>Personas
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'empleados') ? 'active' : '' ?>" 
                       href="/nicafood/modules/empleados/index.php">
                        <i class="fas fa-id-badge me-1"></i>Empleados
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'pedidos') ? 'active' : '' ?>" 
                       href="/nicafood/modules/pedidos/index.php">
                        <i class="fas fa-clipboard-list me-1"></i>Pedidos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'inventario') ? 'active' : '' ?>" 
                       href="/nicafood/modules/inventario/index.php">
                        <i class="fas fa-boxes me-1"></i>Inventario
                    </a>
                </li>
            </ul>
            
            <div class="d-flex align-items-center text-white">
                <span class="me-3">
                    <i class="fas fa-user-circle me-1"></i>
                    <?= htmlspecialchars($_SESSION['nombre'] ?? 'Usuario') ?>
                    <span class="badge bg-light text-dark ms-1"><?= htmlspecialchars($_SESSION['rol'] ?? '') ?></span>
                </span>
                <a href="/nicafood/auth/logout.php" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- Top Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top" style="margin-left: 250px;">
    <div class="container-fluid">
        <button class="btn btn-link d-md-none" onclick="toggleSidebar()">
            <i class="fas fa-bars fa-lg"></i>
        </button>
        
        <div class="d-flex align-items-center">
            <span class="text-muted me-3">
                <i class="fas fa-calendar me-2"></i>
                <?php echo date('d/m/Y'); ?>
            </span>
            
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                        <?php echo strtoupper(substr($_SESSION['nombre'] ?? 'U', 0, 1)); ?>
                    </div>
                    <span class="d-none d-md-inline"><?php echo htmlspecialchars($_SESSION['nombre'] ?? 'Usuario'); ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow">
                    <li><a class="dropdown-item" href="dashboard.php"><i class="fas fa-chart-line me-2"></i>Dashboard</a></li>
                    <li><a class="dropdown-item" href="index.php"><i class="fas fa-home me-2"></i>Inicio</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<style>
.navbar[style*="margin-left"] {
    width: calc(100% - 250px);
    transition: all 0.3s;
}

@media (max-width: 768px) {
    .navbar[style*="margin-left"] {
        margin-left: 0 !important;
        width: 100% !important;
    }
}
</style>
<?php endif; ?>