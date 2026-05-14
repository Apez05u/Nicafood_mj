<?php
require_once 'config/database.php';

$combos = obtenerCombosActivos();
$categorias = obtenerCategoriasProductos();

// ✅ FUNCIÓN ORIGINAL SIN CAMBIOS - Mapeo exacto de imágenes
function obtenerImagen($codigo, $nombre, $categoria) {
    $mapa = [
        'PROD001' => 'comida1.png', 'Sandwich' => 'comida1.png',
        'PROD002' => 'comida2.png', 'Pizza' => 'comida2.png',
        'PROD003' => 'comida3.png', 'Hamburguesa' => 'comida3.png',
        'PROD004' => 'comida4.png', 'Ensalada' => 'comida4.png',
        'PROD005' => 'comida5.png', 'Tacos' => 'comida5.png',
        'PROD009' => 'comida8.png', 'Gaseosa' => 'comida8.png',
        'PROD006' => 'comida6.png', 'Pupusa' => 'comida6.png',
        'PROD007' => 'agua-Fuente.png', 'Agua Mineral' => 'agua-Fuente.png',
        'PROD008' => 'cafe.png', 'Café' => 'cafe.png',
        'PROD010' => 'jugo_Natural.png', 'Jugo de Naranja' => 'jugo_Natural.png',
        'PROD011' => 'limonada.png', 'Limonada' => 'limonada.png',
        'PROD012' => 'rojita.png', 'Rojita' => 'rojita.png',
        'PROD013' => 'te.png', 'Té Helado' => 'te.png',
        'PROD014' => 'comida7.png', 'Hamburguesa Clásica' => 'comida7.png',
        'POST001' => 'alfajores.png', 'Alfajores' => 'alfajores.png',
        'POST002' => 'brownie.png', 'Brownie' => 'brownie.png',
        'POST003' => 'flan.png', 'Flan' => 'flan.png',
        'POST004' => 'pay_limon.png', 'Pay de Limón' => 'pay_limon.png',
        'POST005' => 'pastel_chocolate.png', 'Pastel de Chocolate' => 'pastel_chocolate.png',
        'POST006' => 'gelatina.png', 'Gelatina' => 'gelatina.png',
        'POST007' => 'helado.png', 'Helado' => 'helado.png',
        'POST008' => 'crepas.png', 'Crepas' => 'crepas.png',
        'POST009' => 'enrejados.png', 'Enrejados' => 'enrejados.png',
        'POST010' => 'frutas.png', 'Frutas' => 'frutas.png',
    ];
    
    if (isset($mapa[$codigo])) return 'images/' . $mapa[$codigo];
    foreach ($mapa as $clave => $img) {
        if (stripos($nombre, $clave) !== false) return 'images/' . $img;
    }
    if (stripos($categoria, 'bebida') !== false) return 'images/gaseosa.png';
    if (stripos($categoria, 'postre') !== false) return 'images/brownie.png';
    return 'images/comida1.png';
}

$titulo_pagina = 'NicaFood - Menú';
include 'includes/header.php';
?>

<!-- Navbar Superior -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">
            <i class="fas fa-utensils me-2"></i>NicaFood
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="index.php">
                        <i class="fas fa-home me-1"></i> Inicio
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#menu">
                        <i class="fas fa-utensils me-1"></i> Menú
                    </a>
                </li>
                <?php if (isset($_SESSION['id_usuario'])): ?>
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">
                        <i class="fas fa-tachometer-alt me-1"></i> Panel
                    </a>
                </li>
                <?php endif; ?>
            </ul>
            
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link position-relative" href="#" data-bs-toggle="modal" data-bs-target="#modalCarrito">
                        <i class="fas fa-shopping-cart me-1"></i> Carrito
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark" id="cart-count" style="font-size: 0.7rem;">
                            0
                        </span>
                    </a>
                </li>
                <?php if (isset($_SESSION['id_usuario'])): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-1"></i>
                        <?= htmlspecialchars($_SESSION['nombre'] ?? 'Usuario') ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="dashboard.php"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                        <li><a class="dropdown-item" href="checkout.php"><i class="fas fa-cash-register me-2"></i>Ventas</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión</a></li>
                    </ul>
                </li>
                <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">
                        <i class="fas fa-sign-in-alt me-1"></i> Ingresar
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<header class="hero-section" style="margin-top: 56px;">
    <div class="container text-center py-5">
        <h1 class="display-4 fw-bold mb-3">
            <i class="fas fa-utensils me-2"></i>Bienvenido a NicaFood
        </h1>
        <p class="lead mb-4">Los mejores sabores de Nicaragua, preparados con amor y entregados con rapidez</p>
        <a href="#menu" class="btn btn-warning btn-lg rounded-pill px-5">
            <i class="fas fa-arrow-down me-2"></i>Explorar Menú
        </a>
    </div>
</header>

<!-- Search & Filters -->
<div class="container mb-4">
    <div class="search-section bg-white rounded-3 shadow-sm p-4">
        <div class="search-box mb-3">
            <div class="input-group input-group-lg">
                <span class="input-group-text bg-light border-0">
                    <i class="fas fa-search text-muted"></i>
                </span>
                <input type="text" class="form-control border-0 bg-light" id="searchInput" placeholder="Buscar productos, combos o categorías...">
            </div>
        </div>
        
        <div class="filter-buttons d-flex gap-2 flex-wrap justify-content-center">
            <button class="btn btn-primary rounded-pill px-4 active" data-filter="all">Todos</button>
            <button class="btn btn-outline-primary rounded-pill px-4" data-filter="combos">Combos</button>
            <button class="btn btn-outline-primary rounded-pill px-4" data-filter="comidas">Comidas</button>
            <button class="btn btn-outline-primary rounded-pill px-4" data-filter="bebidas">Bebidas</button>
            <button class="btn btn-outline-primary rounded-pill px-4" data-filter="postres">Postres</button>
        </div>
    </div>
</div>

<!-- Main Menu -->
<main class="container py-4" id="menu">
    
    <!-- Combos Section -->
    <?php if (!empty($combos)): ?>
    <section class="mb-5" data-category="combos">
        <h2 class="section-title mb-4">
            <i class="fas fa-gift me-2 text-warning"></i>Combos Especiales
        </h2>
        <div class="row g-4">
            <?php foreach ($combos as $i => $combo): 
                $img = 'images/combo' . ($i+1) . '.png';
                if (!file_exists($img)) $img = 'images/combo1.png';
            ?>
            <div class="col-md-4 col-sm-6 product-card" data-name="<?= strtolower(htmlspecialchars($combo['nombre'])) ?>">
                <div class="card card-prod h-100 border-0 shadow-sm" onclick="agregar('<?= addslashes($combo['nombre']) ?>', <?= $combo['precio_combo'] ?>, 'combo', 0)">
                    <div class="card-img-wrapper position-relative">
                        <img src="<?= $img ?>" class="card-img-top" alt="<?= htmlspecialchars($combo['nombre']) ?>">
                        <span class="badge bg-warning position-absolute top-0 end-0 m-2">🔥 Promo</span>
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold"><?= htmlspecialchars($combo['nombre']) ?></h5>
                        <p class="price text-primary fw-bold fs-4 mb-3">C$<?= number_format($combo['precio_combo'], 2) ?></p>
                        <button class="btn btn-outline-primary w-100 rounded-pill">
                            <i class="fas fa-plus me-2"></i>Agregar
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <hr class="my-5">
    <?php endif; ?>

    <!-- Products by Category -->
    <?php foreach ($categorias as $cat): 
        $productos = obtenerProductosMenu($cat['id_categoria']);
        if (empty($productos)) continue;
        
        $catSlug = strtolower(str_replace([' ', 'í', 'é'], ['', 'i', 'e'], $cat['nombre']));
        $sectionClass = in_array($catSlug, ['comidas', 'bebidas', 'postres']) ? $catSlug : 'comidas';
        $icons = ['comidas' => '🍽️', 'bebidas' => '🥤', 'postres' => '🍰'];
    ?>
    <section class="mb-5" data-category="<?= $sectionClass ?>">
        <h2 class="section-title mb-4">
            <span><?= $icons[$sectionClass] ?? '🍽️' ?></span> <?= htmlspecialchars($cat['nombre']) ?>
        </h2>
        <div class="row g-4">
            <?php foreach ($productos as $prod): 
                $precio = $prod['precio_menu'] ?? $prod['precio_venta'];
                $img = obtenerImagen($prod['codigo_producto'] ?? '', $prod['nombre'], $cat['nombre']);
                $prodName = strtolower(str_replace([' ', '-', '_'], '', htmlspecialchars($prod['nombre'])));
            ?>
            <div class="col-lg-3 col-md-4 col-sm-6 product-card" 
                 data-name="<?= $prodName ?>" 
                 data-category="<?= $sectionClass ?>">
                <div class="card card-prod h-100 border-0 shadow-sm" onclick="agregar('<?= addslashes($prod['nombre']) ?>', <?= $precio ?>, 'producto', <?= $prod['id_producto'] ?>)">
                    <div class="card-img-wrapper">
                        <img src="<?= $img ?>" class="card-img-top" alt="<?= htmlspecialchars($prod['nombre']) ?>">
                    </div>
                    <div class="card-body text-center">
                        <h6 class="card-title fw-bold"><?= htmlspecialchars($prod['nombre']) ?></h6>
                        <p class="price text-primary fw-bold fs-5 mb-3">C$<?= number_format($precio, 2) ?></p>
                        <button class="btn btn-outline-primary w-100 rounded-pill">
                            <i class="fas fa-plus me-2"></i>Agregar
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endforeach; ?>
    
</main>

<!-- Floating Cart Button (Mobile) -->
<div class="floating-cart d-md-none">
    <button class="btn btn-primary btn-lg rounded-pill px-4" onclick="document.getElementById('modalCarrito').click()" id="btnCheckout" disabled>
        <i class="fas fa-shopping-cart me-2"></i>Ver Pedido (<span id="cart-total-float">0</span>)
    </button>
</div>

<!-- Cart Modal -->
<div class="modal fade" id="modalCarrito" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-shopping-cart me-2"></i>Tu Pedido
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="cart-modal-body">
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-shopping-basket fa-3x mb-3 opacity-25"></i>
                    <p class="fs-5">Tu carrito está vacío</p>
                    <small>Agrega productos del menú para continuar</small>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <div class="cart-total w-100 mb-3">
                    <div class="d-flex justify-content-between align-items-center fs-5">
                        <span>Total:</span>
                        <strong class="text-primary fs-4" id="modal-total">C$0.00</strong>
                    </div>
                </div>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Seguir comprando</button>
                <button type="button" class="btn btn-success" onclick="irACheckout()">
                    <i class="fas fa-arrow-right me-2"></i>Proceder al Pago
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Hero Section */
.hero-section {
    background: linear-gradient(135deg, #0b3185 0%, #06225e 100%);
    color: white;
    padding: 60px 0;
}

/* Search Section */
.search-section {
    margin-top: -30px;
    position: relative;
    z-index: 10;
}

/* Product Cards */
.card-prod {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    cursor: pointer;
    border-radius: 15px;
    overflow: hidden;
}

.card-prod:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 35px rgba(0,0,0,0.15) !important;
}

.card-img-wrapper {
    height: 200px;
    overflow: hidden;
    background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
}

.card-img-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.card-prod:hover .card-img-wrapper img {
    transform: scale(1.1);
}

.card-body {
    padding: 20px;
}

.price {
    font-size: 1.5rem;
}

/* Section Titles */
.section-title {
    font-size: 1.8rem;
    font-weight: 700;
    color: #1e293b;
}

/* Filter Buttons */
.filter-buttons .btn {
    transition: all 0.3s ease;
}

.filter-buttons .btn.active {
    background: #0b3185;
    border-color: #0b3185;
}

/* Floating Cart (Mobile) */
.floating-cart {
    position: fixed;
    bottom: 20px;
    right: 20px;
    left: 20px;
    z-index: 1000;
}

.floating-cart .btn {
    width: 100%;
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
}

/* Cart Modal */
.cart-total {
    background: #f8fafc;
    border-radius: 10px;
    padding: 15px;
}

/* Responsive */
@media (max-width: 768px) {
    .hero-section h1 {
        font-size: 2rem;
    }
    
    .search-section {
        margin-top: 0;
    }
    
    .floating-cart {
        display: block !important;
    }
}
</style>

<script>
// ==================== CARRITO ====================
let carrito = JSON.parse(localStorage.getItem('nicafood_cart')) || [];

function agregar(nombre, precio, tipo, id_producto) {
    const existe = carrito.find(i => i.nombre === nombre && i.tipo === tipo);
    
    if (existe) {
        existe.cantidad++;
        showToast(`✅ ${nombre} agregado`, 'success');
    } else {
        carrito.push({ 
            nombre, 
            precio: parseFloat(precio), 
            tipo, 
            id_producto: id_producto || 0, 
            cantidad: 1 
        });
        showToast(`🛒 ${nombre} añadido al carrito`, 'success');
    }
    
    localStorage.setItem('nicafood_cart', JSON.stringify(carrito));
    actualizarUI();
}

function actualizarUI() {
    const totalItems = carrito.reduce((s, i) => s + i.cantidad, 0);
    const totalMonto = carrito.reduce((s, i) => s + (i.precio * i.cantidad), 0);
    
    document.getElementById('cart-count').textContent = totalItems;
    document.getElementById('cart-total-float').textContent = 'C$' + totalMonto.toFixed(2);
    
    const btnCheckout = document.getElementById('btnCheckout');
    if (btnCheckout) {
        btnCheckout.disabled = carrito.length === 0;
    }
    
    renderizarCarritoModal();
}

function renderizarCarritoModal() {
    const container = document.getElementById('cart-modal-body');
    const totalEl = document.getElementById('modal-total');
    
    if (!carrito || carrito.length === 0) {
        container.innerHTML = `
            <div class="text-center py-5 text-muted">
                <i class="fas fa-shopping-basket fa-3x mb-3 opacity-25"></i>
                <p class="fs-5">Tu carrito está vacío</p>
                <small>Agrega productos del menú para continuar</small>
            </div>
        `;
        totalEl.textContent = 'C$0.00';
        return;
    }
    
    let html = '';
    let total = 0;
    
    carrito.forEach((item, idx) => {
        const subtotal = item.precio * item.cantidad;
        total += subtotal;
        
        html += `
            <div class="cart-item mb-3 pb-3 border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong>${item.nombre}</strong>
                        <br><small class="text-muted">C$${item.precio.toFixed(2)} c/u</small>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button class="btn btn-sm btn-outline-secondary" onclick="actualizarCantidad(${idx}, -1)">-</button>
                        <span class="fw-bold">${item.cantidad}</span>
                        <button class="btn btn-sm btn-outline-secondary" onclick="actualizarCantidad(${idx}, 1)">+</button>
                        <button class="btn btn-sm btn-outline-danger ms-2" onclick="quitar(${idx})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <div class="text-end mt-2">
                    <strong class="text-primary">C$${subtotal.toFixed(2)}</strong>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
    totalEl.textContent = 'C$' + total.toFixed(2);
}

function actualizarCantidad(index, cambio) {
    if (carrito[index]) {
        carrito[index].cantidad += cambio;
        if (carrito[index].cantidad <= 0) {
            carrito.splice(index, 1);
        }
        localStorage.setItem('nicafood_cart', JSON.stringify(carrito));
        actualizarUI();
    }
}

function quitar(index) {
    carrito.splice(index, 1);
    localStorage.setItem('nicafood_cart', JSON.stringify(carrito));
    actualizarUI();
}

function irACheckout() {
    if (carrito.length === 0) {
        showToast('⚠️ Agrega productos primero', 'warning');
        return;
    }
    window.location.href = 'checkout.php';
}

function showToast(message, type = 'success') {
    // Crear toast dinámico
    const toastContainer = document.getElementById('toastContainer') || createToastContainer();
    
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type === 'error' ? 'danger' : (type === 'warning' ? 'warning' : 'success')} border-0 mb-2`;
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    toastContainer.appendChild(toast);
    const bsToast = new bootstrap.Toast(toast, { delay: 3000 });
    bsToast.show();
    
    toast.addEventListener('hidden.bs.toast', () => toast.remove());
}

function createToastContainer() {
    const container = document.createElement('div');
    container.id = 'toastContainer';
    container.className = 'position-fixed bottom-0 end-0 p-3';
    container.style.zIndex = '9999';
    document.body.appendChild(container);
    return container;
}

// ==================== BÚSQUEDA Y FILTROS ====================
document.getElementById('searchInput')?.addEventListener('input', function(e) {
    const term = e.target.value.toLowerCase().trim();
    document.querySelectorAll('.product-card').forEach(card => {
        const name = card.dataset.name || '';
        const category = card.dataset.category || '';
        const matches = !term || name.includes(term) || category.includes(term);
        card.style.display = matches ? '' : 'none';
    });
});

document.querySelectorAll('.filter-buttons .btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.filter-buttons .btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        const filter = this.dataset.filter;
        document.querySelectorAll('section[data-category]').forEach(section => {
            const cat = section.dataset.category;
            if (filter === 'all' || cat === filter) {
                section.style.display = '';
            } else {
                section.style.display = 'none';
            }
        });
    });
});

// ==================== INICIALIZACIÓN ====================
document.addEventListener('DOMContentLoaded', function() {
    actualizarUI();
    
    // Smooth scroll
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>