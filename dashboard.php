<?php
/**
 * NicaFood ERP - Dashboard Principal
 * Archivo: dashboard.php
 * 
 * Compatible con sidebar colapsable
 */
session_start();
require_once 'config/database.php';

// Verificar sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

$db = getDB();

// ================= ESTADÍSTICAS =================
$stats = [];

// Ventas hoy
$stmt = $db->query("SELECT COUNT(*) as total, COALESCE(SUM(total_pagar), 0) as monto 
                    FROM factura_cabecera 
                    WHERE DATE(fecha_emision) = CURDATE()");
$stats['ventas_hoy'] = $stmt->fetch();

// Total clientes
$stats['total_clientes'] = $db->query("SELECT COUNT(*) FROM cliente")->fetchColumn();

// Stock bajo
$stats['stock_bajo'] = $db->query("SELECT COUNT(*) FROM insumo WHERE stock_minimo > 0 
                                   AND (SELECT COALESCE(SUM(cantidad_actual), 0) 
                                        FROM lote WHERE id_insumo = insumo.id_insumo) < stock_minimo")->fetchColumn();

// Empleados activos
$stats['empleados_activos'] = $db->query("SELECT COUNT(*) FROM empleado WHERE estado = 'Activo'")->fetchColumn();

// Pedidos del mes
$stats['pedidos_mes'] = $db->query("SELECT COUNT(*) FROM factura_cabecera 
                                    WHERE MONTH(fecha_emision) = MONTH(CURDATE())")->fetchColumn();

// Últimas ventas
$ultimas_ventas = $db->query("SELECT fc.numero_factura, fc.total_pagar, fc.fecha_emision, 
                                     p.primer_nombre, p.Apellidos 
                              FROM factura_cabecera fc
                              LEFT JOIN cliente c ON fc.id_cliente = c.id_cliente
                              LEFT JOIN persona p ON c.id_persona = p.id_persona
                              ORDER BY fc.fecha_emision DESC LIMIT 5")->fetchAll();

$titulo_pagina = 'Dashboard - NicaFood ERP';
include 'includes/header.php';
include 'includes/sidebar.php';
?>

<div class="main-wrapper">
    <div class="container-fluid py-4">
        
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0">
                    <i class="fas fa-tachometer-alt me-2 text-primary"></i>Panel de Control
                </h2>
                <small class="text-muted">
                    Bienvenido, <?= htmlspecialchars($_SESSION['nombre'] ?? 'Usuario') ?>
                </small>
            </div>
            <div class="text-end">
                <span class="text-muted">
                    <i class="fas fa-calendar me-2"></i>
                    <?= date('d/m/Y') ?>
                </span>
            </div>
        </div>
        
        <!-- Tarjetas de Estadísticas -->
        <div class="row g-4 mb-4">
            <!-- Ventas Hoy -->
            <div class="col-xl-3 col-md-6">
                <a href="checkout.php" class="text-decoration-none">
                    <div class="card border-0 shadow-sm h-100 hover-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="fas fa-dollar-sign fa-2x text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-1">Ventas Hoy</h6>
                                    <h3 class="mb-0 fw-bold">C$<?= number_format($stats['ventas_hoy']['monto'] ?? 0, 2) ?></h3>
                                    <small class="text-muted"><?= $stats['ventas_hoy']['total'] ?? 0 ?> facturas</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            
            <!-- Empleados Activos -->
            <div class="col-xl-3 col-md-6">
                <a href="modules/rrhh/index.php" class="text-decoration-none">
                    <div class="card border-0 shadow-sm h-100 hover-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="fas fa-users fa-2x text-success"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-1">Empleados Activos</h6>
                                    <h3 class="mb-0 fw-bold"><?= $stats['empleados_activos'] ?? 0 ?></h3>
                                    <small class="text-success">Ver RRHH →</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            
            <!-- Stock Bajo -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 hover-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">Stock Bajo</h6>
                                <h3 class="mb-0 fw-bold"><?= $stats['stock_bajo'] ?? 0 ?></h3>
                                <small class="text-muted">Insumos por reordenar</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Pedidos del Mes -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 hover-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="bg-info bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-calendar-alt fa-2x text-info"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">Pedidos del Mes</h6>
                                <h3 class="mb-0 fw-bold"><?= $stats['pedidos_mes'] ?? 0 ?></h3>
                                <small class="text-muted">Facturas emitidas</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Accesos Rápidos -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center py-4">
                        <i class="fas fa-users fa-3x text-primary mb-3"></i>
                        <h5 class="card-title fw-bold">Recursos Humanos</h5>
                        <p class="card-text text-muted">Gestiona empleados, contrataciones y nómina</p>
                        <a href="modules/rrhh/index.php" class="btn btn-primary rounded-pill px-4">
                            <i class="fas fa-arrow-right me-2"></i>Ir a RRHH
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center py-4">
                        <i class="fas fa-shopping-cart fa-3x text-success mb-3"></i>
                        <h5 class="card-title fw-bold">Ventas POS</h5>
                        <p class="card-text text-muted">Realiza ventas y gestiona pedidos</p>
                        <a href="index.php" class="btn btn-success rounded-pill px-4">
                            <i class="fas fa-arrow-right me-2"></i>Ir a Ventas
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center py-4">
                        <i class="fas fa-box fa-3x text-warning mb-3"></i>
                        <h5 class="card-title fw-bold">Inventario</h5>
                        <p class="card-text text-muted">Controla el stock de insumos</p>
                        <button class="btn btn-warning rounded-pill px-4" disabled>
                            <i class="fas fa-clock me-2"></i>Próximamente
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Últimas Ventas -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-clock me-2 text-primary"></i>Últimas Ventas</h5>
                <a href="checkout.php" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-plus me-1"></i>Nueva Venta
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Factura</th>
                                <th>Cliente</th>
                                <th>Fecha</th>
                                <th class="text-end">Total</th>
                                <th class="text-center">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($ultimas_ventas) > 0): ?>
                                <?php foreach ($ultimas_ventas as $venta): ?>
                                <tr>
                                    <td>
                                        <span class="fw-bold text-primary"><?= htmlspecialchars($venta['numero_factura']) ?></span>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($venta['primer_nombre'] . ' ' . $venta['Apellidos']) ?>
                                    </td>
                                    <td><?= date('d/m/Y H:i', strtotime($venta['fecha_emision'])) ?></td>
                                    <td class="text-end">
                                        <strong class="text-success">C$<?= number_format($venta['total_pagar'], 2) ?></strong>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success rounded-pill">Pagada</span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-5">
                                        <i class="fas fa-inbox fa-3x mb-3 opacity-25"></i>
                                        <p class="mb-0">No hay ventas registradas aún</p>
                                        <small>Comienza registrando tu primera venta</small>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
    </div>
</div>

<!-- Estilos adicionales -->
<style>
.hover-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    cursor: pointer;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.12) !important;
}

.hover-card a {
    color: inherit;
    text-decoration: none;
}

.card:hover {
    box-shadow: 0 10px 30px rgba(0,0,0,0.12) !important;
}
</style>
<!-- Ejemplo: Agregar módulo de Reportes -->
<div class="col-md-4">
    <div class="card h-100 border-0 shadow-sm">
        <div class="card-body text-center py-4">
            <i class="fas fa-chart-bar fa-3x text-info mb-3"></i>
            <h5 class="card-title fw-bold">Reportes</h5>
            <p class="card-text text-muted">Genera reportes de ventas y empleados</p>
            <button class="btn btn-info rounded-pill px-4" disabled>
                <i class="fas fa-clock me-2"></i>Próximamente
            </button>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>