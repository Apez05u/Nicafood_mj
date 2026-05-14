<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Detectar nivel de profundidad para ajustar rutas
$uri = $_SERVER['REQUEST_URI'];
$depth = substr_count($uri, '/') - 1;
$base_path = (strpos($uri, '/modules/') !== false) ? '../../' : '';

// Función helper para detectar página activa
function isActive($page, $module = null) {
    $current = $_SERVER['PHP_SELF'] ?? '';
    if ($module) {
        return strpos($current, $module) !== false && strpos($current, $page) !== false;
    }
    return basename($current) === $page;
}
?>

<aside class="sidebar" id="sidebar">
    <!-- Toggle Button -->
    <button class="sidebar-toggle" id="sidebarToggle" onclick="toggleSidebar()" title="Colapsar menú">
        <i class="fas fa-bars"></i>
    </button>
    
    <!-- Logo / Brand -->
    <div class="sidebar-header">
        <a href="<?= $base_path ?>dashboard.php" class="sidebar-brand">
            <i class="fas fa-utensils"></i>
            <div class="brand-text">
                <h4 class="mb-0">NicaFood</h4>
                <small class="text-muted d-block">ERP System v1.0</small>
            </div>
        </a>
    </div>
    
    <!-- Menú de Navegación -->
    <nav class="sidebar-nav flex-grow-1">
        <ul class="nav flex-column">
            
            <!-- Dashboard -->
            <li class="nav-item">
                <a class="nav-link <?= isActive('dashboard.php') ? 'active' : '' ?>" href="<?= $base_path ?>dashboard.php">
                    <i class="fas fa-chart-line"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>
            
            <!-- Menú Público / Ventas -->
            <li class="nav-item">
                <a class="nav-link <?= isActive('index.php') || isActive('checkout.php') ? 'active' : '' ?>" href="<?= $base_path ?>index.php">
                    <i class="fas fa-home"></i>
                    <span class="nav-text">Inicio / Menú</span>
                </a>
            </li>
            
            <!-- Ventas / Punto de Venta -->
            <li class="nav-item">
                <a class="nav-link <?= isActive('checkout.php') ? 'active' : '' ?>" href="<?= $base_path ?>checkout.php">
                    <i class="fas fa-cash-register"></i>
                    <span class="nav-text">Ventas POS</span>
                </a>
            </li>
            
            <!-- Separador: Módulos Administrativos -->
            <li class="nav-item mt-3">
                <hr class="sidebar-divider">
                <small class="nav-text text-muted px-3 d-block mb-2">ADMINISTRACIÓN</small>
            </li>
            
            <!-- RECURSOS HUMANOS -->
            <li class="nav-item">
                <a class="nav-link <?= isActive('index.php', 'rrhh') ? 'active' : '' ?>" href="<?= $base_path ?>modules/rrhh/index.php">
                    <i class="fas fa-users"></i>
                    <span class="nav-text">Recursos Humanos</span>
                    <span class="badge bg-success ms-auto nav-text">Nuevo</span>
                </a>
            </li>
            
            <!-- Productos (Próximamente) -->
            <li class="nav-item">
                <a class="nav-link disabled" href="#" title="Próximamente">
                    <i class="fas fa-box"></i>
                    <span class="nav-text">Productos</span>
                    <small class="text-muted ms-auto nav-text">(Pronto)</small>
                </a>
            </li>
            
            <!-- Clientes (Próximamente) -->
            <li class="nav-item">
                <a class="nav-link disabled" href="#" title="Próximamente">
                    <i class="fas fa-users"></i>
                    <span class="nav-text">Clientes</span>
                </a>
            </li>
            
            <!-- Reportes (Próximamente) -->
            <li class="nav-item">
                <a class="nav-link disabled" href="#" title="Próximamente">
                    <i class="fas fa-chart-bar"></i>
                    <span class="nav-text">Reportes</span>
                </a>
            </li>
            
            <!-- Separador Final -->
            <li class="nav-item mt-3">
                <hr class="sidebar-divider">
            </li>
            
            <!-- Cerrar Sesión -->
            <li class="nav-item">
                <a class="nav-link text-danger" href="<?= $base_path ?>logout.php" onclick="showLogoutModal(event)">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="nav-text">Cerrar Sesión</span>
                </a>
            </li>
            
        </ul>
    </nav>
    
    <!-- Footer con Info del Usuario -->
    <div class="sidebar-footer">
        <div class="user-mini">
            <div class="user-avatar">
                <?= strtoupper(substr($_SESSION['nombre'] ?? 'U', 0, 1)) ?>
            </div>
            <div class="user-info">
                <div class="user-name"><?= htmlspecialchars($_SESSION['nombre'] ?? 'Usuario') ?></div>
                <div class="user-role"><?= htmlspecialchars($_SESSION['rol'] ?? '') ?></div>
            </div>
        </div>
    </div>
</aside>

<!-- Overlay para móvil -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<!-- ================= MODAL DE CIERRE DE SESIÓN MODERNO ================= -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-0 pt-3 px-4">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4 pt-0">
                <!-- Icono Animado -->
                <div class="logout-icon-container mb-4">
                    <div class="logout-icon">
                        <i class="fas fa-sign-out-alt"></i>
                    </div>
                </div>
                
                <!-- Título -->
                <h4 class="modal-title mb-2 fw-bold" id="logoutModalLabel">
                    ¿Cerrar Sesión?
                </h4>
                
                <!-- Descripción -->
                <p class="text-muted mb-4">
                    Estás a punto de cerrar tu sesión en <strong>NicaFood ERP</strong>.<br>
                    ¿Deseas continuar?
                </p>
                
                <!-- Botones -->
                <div class="d-grid gap-2">
                    <a href="logout.php" class="btn btn-danger btn-lg rounded-pill" id="confirmLogout">
                        <i class="fas fa-sign-out-alt me-2"></i>Sí, Cerrar Sesión
                    </a>
                    <button type="button" class="btn btn-outline-secondary btn-lg rounded-pill" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                </div>
                
                <!-- Info Adicional -->
                <div class="mt-4 pt-3 border-top">
                    <small class="text-muted">
                        <i class="fas fa-shield-alt me-1"></i>
                        Sesión segura • NicaFood ERP
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ================= CSS ================= -->
<style>
:root {
    --sidebar-width: 260px;
    --sidebar-collapsed-width: 70px;
    --sidebar-bg: linear-gradient(180deg, #0b3185 0%, #06225e 100%);
    --sidebar-text: rgba(255,255,255,0.9);
    --sidebar-text-muted: rgba(255,255,255,0.6);
    --sidebar-hover: rgba(255,255,255,0.15);
    --sidebar-active: rgba(255,255,255,0.25);
    --sidebar-border: rgba(255,255,255,0.1);
    --transition-speed: 0.3s;
}

/* Sidebar Base */
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    width: var(--sidebar-width);
    background: var(--sidebar-bg);
    color: white;
    display: flex;
    flex-direction: column;
    padding: 0;
    z-index: 1030;
    transition: width var(--transition-speed) ease;
    box-shadow: 4px 0 20px rgba(0,0,0,0.15);
    overflow: hidden;
}

.sidebar.collapsed {
    width: var(--sidebar-collapsed-width);
}

.sidebar.collapsed .brand-text,
.sidebar.collapsed .nav-text,
.sidebar.collapsed .user-info,
.sidebar.collapsed .sidebar-divider + small {
    display: none;
}

.sidebar.collapsed .sidebar-header {
    padding: 15px 10px;
}

.sidebar.collapsed .sidebar-brand {
    justify-content: center;
}

.sidebar.collapsed .sidebar-brand i {
    font-size: 1.8rem;
}

.sidebar.collapsed .nav-link {
    justify-content: center;
    padding: 12px 10px;
}

.sidebar.collapsed .nav-link i {
    margin-right: 0;
    font-size: 1.2rem;
}

.sidebar.collapsed .sidebar-footer {
    justify-content: center;
}

.sidebar.collapsed .user-avatar {
    margin-right: 0;
}

/* Toggle Button */
.sidebar-toggle {
    position: absolute;
    top: 15px;
    right: 10px;
    background: rgba(255,255,255,0.1);
    border: none;
    color: white;
    width: 30px;
    height: 30px;
    border-radius: 8px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    z-index: 10;
}

.sidebar-toggle:hover {
    background: rgba(255,255,255,0.2);
    transform: scale(1.1);
}

/* Header / Logo */
.sidebar-header {
    padding: 20px;
    border-bottom: 1px solid var(--sidebar-border);
    transition: padding var(--transition-speed) ease;
}

.sidebar-brand {
    display: flex;
    align-items: center;
    gap: 12px;
    color: white;
    text-decoration: none;
    padding: 5px 0;
}

.sidebar-brand i {
    font-size: 1.5rem;
    color: #ff9e00;
    flex-shrink: 0;
}

.sidebar-brand h4 {
    font-weight: 700;
    font-size: 1.3rem;
    line-height: 1.2;
    white-space: nowrap;
}

.sidebar-brand small {
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    white-space: nowrap;
}

/* Navegación */
.sidebar-nav {
    padding: 15px 10px;
    overflow-y: auto;
    overflow-x: hidden;
    flex-grow: 1;
}

.sidebar-nav::-webkit-scrollbar {
    width: 4px;
}

.sidebar-nav::-webkit-scrollbar-track {
    background: transparent;
}

.sidebar-nav::-webkit-scrollbar-thumb {
    background: var(--sidebar-hover);
    border-radius: 4px;
}

.nav flex-column {
    gap: 4px;
}

.nav-item {
    list-style: none;
}

.nav-link {
    color: var(--sidebar-text);
    padding: 12px 16px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    gap: 12px;
    text-decoration: none;
    font-weight: 500;
    font-size: 0.95rem;
    transition: all 0.2s ease;
    position: relative;
    white-space: nowrap;
}

.nav-link i {
    width: 20px;
    text-align: center;
    font-size: 1rem;
    opacity: 0.9;
    flex-shrink: 0;
}

.nav-link:hover {
    background: var(--sidebar-hover);
    color: white;
}

.nav-link:hover i {
    opacity: 1;
}

.nav-link.active {
    background: var(--sidebar-active);
    color: white;
    font-weight: 600;
}

.nav-link.active::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 4px;
    height: 70%;
    background: #ff9e00;
    border-radius: 0 4px 4px 0;
}

.nav-link.active i {
    color: #ff9e00;
}

.nav-link .badge {
    font-size: 0.7rem;
    padding: 3px 8px;
    font-weight: 600;
}

.nav-link.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: none;
}

.nav-link.disabled:hover {
    background: transparent;
}

.sidebar-divider {
    border-color: var(--sidebar-border);
    margin: 15px 10px !important;
    opacity: 0.5;
}

.sidebar-divider + small {
    padding: 0 26px 10px;
    font-size: 0.7rem;
    font-weight: 600;
    letter-spacing: 1px;
    text-transform: uppercase;
}

/* Footer de Usuario */
.sidebar-footer {
    padding: 15px 20px;
    border-top: 1px solid var(--sidebar-border);
    background: rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    transition: padding var(--transition-speed) ease;
}

.user-mini {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-grow: 1;
    min-width: 0;
}

.user-avatar {
    width: 40px;
    height: 40px;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1rem;
    flex-shrink: 0;
}

.user-info {
    flex-grow: 1;
    min-width: 0;
}

.user-name {
    font-weight: 600;
    font-size: 0.9rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.user-role {
    font-size: 0.75rem;
    color: var(--sidebar-text-muted);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Wrapper para contenido principal */
.main-wrapper {
    margin-left: var(--sidebar-width);
    min-height: 100vh;
    transition: margin-left var(--transition-speed) ease;
    background: #f8f9fa;
}

.main-wrapper.expanded {
    margin-left: var(--sidebar-collapsed-width);
}

/* Overlay para móvil */
.sidebar-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    z-index: 1029;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.sidebar-overlay.active {
    display: block;
    opacity: 1;
}

/* Responsive */
@media (max-width: 992px) {
    .sidebar {
        transform: translateX(-100%);
        width: var(--sidebar-width) !important;
    }
    
    .sidebar.active {
        transform: translateX(0);
    }
    
    .sidebar.collapsed {
        width: var(--sidebar-width) !important;
    }
    
    .main-wrapper {
        margin-left: 0 !important;
    }
    
    .sidebar-toggle {
        display: none;
    }
}

/* Animaciones */
@keyframes slideIn {
    from { opacity: 0; transform: translateX(-20px); }
    to { opacity: 1; transform: translateX(0); }
}

.sidebar-nav .nav-item {
    animation: slideIn 0.3s ease forwards;
    animation-delay: calc(var(--i, 0) * 0.05s);
}

/* ================= ESTILOS DEL MODAL DE LOGOUT ================= */
#logoutModal .modal-content {
    border-radius: 20px;
    overflow: hidden;
    animation: modalSlideIn 0.3s ease;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-50px) scale(0.9);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

#logoutModal .logout-icon-container {
    position: relative;
}

#logoutModal .logout-icon {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    animation: pulseLogout 2s infinite;
    box-shadow: 0 10px 30px rgba(220, 53, 69, 0.3);
}

#logoutModal .logout-icon i {
    font-size: 2.5rem;
    color: white;
}

@keyframes pulseLogout {
    0%, 100% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7);
    }
    50% {
        transform: scale(1.05);
        box-shadow: 0 0 0 20px rgba(220, 53, 69, 0);
    }
}

#logoutModal .modal-title {
    color: #1e293b;
    font-size: 1.5rem;
}

#logoutModal .btn-danger {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    border: none;
    font-weight: 600;
    padding: 12px;
    transition: all 0.3s ease;
}

#logoutModal .btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(220, 53, 69, 0.4);
    background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
}

#logoutModal .btn-outline-secondary {
    border-width: 2px;
    font-weight: 600;
    padding: 12px;
}

#logoutModal .btn-outline-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
</style>

<!-- ================= JAVASCRIPT ================= -->
<script>
// Toggle sidebar
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const mainWrapper = document.querySelector('.main-wrapper');
    const overlay = document.getElementById('sidebarOverlay');
    
    if (window.innerWidth <= 992) {
        sidebar.classList.toggle('active');
        overlay?.classList.toggle('active');
        document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
    } else {
        sidebar.classList.toggle('collapsed');
        mainWrapper?.classList.toggle('expanded');
        localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
    }
}

// Mostrar modal de logout
function showLogoutModal(event) {
    event.preventDefault();
    const logoutLink = event.currentTarget;
    const modal = new bootstrap.Modal(document.getElementById('logoutModal'));
    
    // Actualizar el href del botón de confirmar
    document.getElementById('confirmLogout').href = logoutLink.href;
    
    modal.show();
}

// Cerrar sidebar al hacer clic fuera en móvil
document.addEventListener('click', function(e) {
    if (window.innerWidth > 992) return;
    
    const sidebar = document.getElementById('sidebar');
    const toggle = document.querySelector('.sidebar-toggle');
    const overlay = document.getElementById('sidebarOverlay');
    
    if (sidebar.classList.contains('active') && 
        !sidebar.contains(e.target) && 
        !toggle?.contains(e.target)) {
        toggleSidebar();
    }
});

// Cerrar sidebar al navegar (en móvil)
document.querySelectorAll('.sidebar .nav-link:not(.disabled)').forEach(link => {
    link.addEventListener('click', function() {
        if (window.innerWidth <= 992 && this.getAttribute('href') !== '#') {
            setTimeout(toggleSidebar, 150);
        }
    });
});

// Añadir delay a animaciones de items del menú
document.querySelectorAll('.sidebar-nav .nav-item').forEach((item, index) => {
    item.style.setProperty('--i', index);
});

// Tecla Escape para cerrar sidebar en móvil
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && window.innerWidth <= 992) {
        const sidebar = document.getElementById('sidebar');
        if (sidebar.classList.contains('active')) {
            toggleSidebar();
        }
    }
});

// Inicialización
document.addEventListener('DOMContentLoaded', function() {
    // Asegurar que el overlay existe
    if (!document.getElementById('sidebarOverlay')) {
        const overlay = document.createElement('div');
        overlay.id = 'sidebarOverlay';
        overlay.className = 'sidebar-overlay';
        overlay.onclick = toggleSidebar;
        document.body.appendChild(overlay);
    }
    
    // Restaurar estado colapsado en desktop
    if (window.innerWidth > 992) {
        const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        if (isCollapsed) {
            document.getElementById('sidebar').classList.add('collapsed');
            document.querySelector('.main-wrapper')?.classList.add('expanded');
        }
    }
});
</script>