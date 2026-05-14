<?php
require_once '../../config/database.php';
$titulo_pagina = 'Pedidos';
include '../../includes/header.php';
include '../../includes/navbar.php';

// Filtros
$estado = $_GET['estado'] ?? '';
$fecha_desde = $_GET['desde'] ?? date('Y-m-d', strtotime('-7 days'));
$fecha_hasta = $_GET['hasta'] ?? date('Y-m-d');

// Consultar pedidos
$sql = "SELECT f.*, p.primer_nombre, p.Apellidos, u.nombre as unidad_nombre, 
               COUNT(fd.id_det_fac) as items_count
        FROM Factura_Cabecera f
        INNER JOIN Persona p ON (
            SELECT id_persona FROM Cliente WHERE id_cliente = f.id_cliente
        ) = p.id_persona
        LEFT JOIN Unidad_Operativa u ON f.id_unidad = u.id_unidad
        LEFT JOIN Factura_Detalle fd ON f.id_factura = fd.id_factura
        WHERE f.fecha_emision BETWEEN :desde AND :hasta";

if ($estado) {
    $sql .= " AND f.estado = :estado";
}

$sql .= " GROUP BY f.id_factura ORDER BY f.fecha_emision DESC LIMIT 50";

$stmt = getDB()->prepare($sql);
$params = [':desde' => "$fecha_desde 00:00:00", ':hasta' => "$fecha_hasta 23:59:59"];
if ($estado) $params[':estado'] = $estado;

$stmt->execute($params);
$pedidos = $stmt->fetchAll();
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-clipboard-list me-2"></i>Gestión de Pedidos</h2>
        <a href="/nicafood/index.php" target="_blank" class="btn btn-outline-primary">
            <i class="fas fa-external-link-alt me-1"></i>Nuevo Pedido (Público)
        </a>
    </div>
    
    <!-- Filtros -->
    <form method="GET" class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-select">
                        <option value="">Todos</option>
                        <option value="Emitida" <?= $estado === 'Emitida' ? 'selected' : '' ?>>Emitida</option>
                        <option value="Pagada" <?= $estado === 'Pagada' ? 'selected' : '' ?>>Pagada</option>
                        <option value="Anulada" <?= $estado === 'Anulada' ? 'selected' : '' ?>>Anulada</option>
                        <option value="Devuelta" <?= $estado === 'Devuelta' ? 'selected' : '' ?>>Devuelta</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Desde</label>
                    <input type="date" name="desde" class="form-control" value="<?= $fecha_desde ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Hasta</label>
                    <input type="date" name="hasta" class="form-control" value="<?= $fecha_hasta ?>">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i>Filtrar
                    </button>
                </div>
            </div>
        </div>
    </form>
    
    <!-- Tabla de pedidos -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Factura</th>
                            <th>Cliente</th>
                            <th>Fecha</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($pedidos): ?>
                            <?php foreach ($pedidos as $p): 
                                $badge = match($p['estado']) {
                                    'Pagada' => 'bg-success',
                                    'Emitida' => 'bg-warning text-dark',
                                    'Anulada' => 'bg-secondary',
                                    'Devuelta' => 'bg-danger',
                                    default => 'bg-light text-dark'
                                };
                            ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($p['numero_factura']) ?></strong>
                                    <br>
                                    <small class="text-muted">#<?= $p['id_factura'] ?></small>
                                </td>
                                <td>
                                    <?= htmlspecialchars($p['primer_nombre']) ?> 
                                    <?= htmlspecialchars($p['Apellidos']) ?>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($p['fecha_emision'])) ?></td>
                                <td><?= $p['items_count'] ?></td>
                                <td class="fw-bold">C$<?= number_format($p['total_pagar'], 2) ?></td>
                                <td><span class="badge <?= $badge ?>"><?= $p['estado'] ?></span></td>
                                <td>
                                    <a href="ver.php?id=<?= $p['id_factura'] ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?php if ($p['estado'] === 'Emitida'): ?>
                                    <button class="btn btn-sm btn-outline-success" 
                                            onclick="marcarPagado(<?= $p['id_factura'] ?>)">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    <i class="fas fa-info-circle me-2"></i>No hay pedidos en este período
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function marcarPagado(idFactura) {
    if (!confirm('¿Marcar esta factura como PAGADA?')) return;
    
    fetch('actualizar_estado.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `id_factura=${idFactura}&estado=Pagada`
    })
    .then(r => r.json())
    .then(data => {
        if (data.exito) {
            showToast('Factura marcada como pagada');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(data.error, 'error');
        }
    });
}
</script>

<?php include '../../includes/footer.php'; ?>