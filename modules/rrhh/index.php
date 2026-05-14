<?php
/**
 * NicaFood ERP - Módulo RRHH: Listado de Empleados
 */
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../../login.php");
    exit;
}

$db = getDB();

$empleados = $db->query("
    SELECT 
        e.id_empleado, e.codigo_emp, e.fecha_ingreso, e.estado,
        p.primer_nombre, p.segundo_nombre, p.Apellidos, 
        p.telefono_principal, p.email,
        c.nombre as cargo_nombre, a.nombre as area_nombre, d.nombre as depto_nombre
    FROM empleado e
    INNER JOIN persona p ON e.id_persona = p.id_persona
    LEFT JOIN cargo c ON e.id_cargo = c.id_cargo
    LEFT JOIN area a ON e.id_area = a.id_area
    LEFT JOIN departamento d ON a.id_depto = d.id_depto
    ORDER BY e.fecha_ingreso DESC
")->fetchAll();

$departamentos = $db->query("SELECT id_depto, nombre FROM departamento ORDER BY nombre")->fetchAll();
$total_empleados = count($empleados);
$activos = count(array_filter($empleados, fn($e) => $e['estado'] === 'Activo'));
$inactivos = count(array_filter($empleados, fn($e) => $e['estado'] !== 'Activo'));

$mensaje = $_GET['mensaje'] ?? '';
$mensajeTexto = '';
if ($mensaje === 'creado') $mensajeTexto = '✅ Empleado registrado correctamente';
elseif ($mensaje === 'actualizado') $mensajeTexto = '✅ Empleado actualizado correctamente';

$titulo_pagina = 'Recursos Humanos - Empleados';
include '../../includes/header.php';
include '../../includes/sidebar.php';
?>

<div class="main-wrapper">
    <div class="container-fluid py-4">
        
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0">
                    <i class="fas fa-users me-2 text-primary"></i>Gestión de Personal
                </h2>
                <small class="text-muted">Administra los empleados de NicaFood</small>
            </div>
            <a href="nuevo_empleado.php" class="btn btn-primary">
                <i class="fas fa-user-plus me-2"></i>Contratar Nuevo Empleado
            </a>
        </div>
        
        <!-- Mensajes -->
        <?php if ($mensajeTexto): ?>
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <?= $mensajeTexto ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        
        <!-- Stats -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-users fa-2x text-primary"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">Total Empleados</h6>
                                <h3 class="mb-0 fw-bold"><?= $total_empleados ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-check-circle fa-2x text-success"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">Activos</h6>
                                <h3 class="mb-0 fw-bold"><?= $activos ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="bg-secondary bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-user-slash fa-2x text-secondary"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">Inactivos</h6>
                                <h3 class="mb-0 fw-bold"><?= $inactivos ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="bg-info bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-building fa-2x text-info"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">Departamentos</h6>
                                <h3 class="mb-0 fw-bold"><?= count($departamentos) ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Filtros -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" id="searchEmpleados" placeholder="Buscar por nombre, cargo o email...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="filterDepartamento">
                            <option value="">Todos los Departamentos</option>
                            <?php foreach ($departamentos as $depto): ?>
                            <option value="<?= htmlspecialchars($depto['nombre']) ?>"><?= htmlspecialchars($depto['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="filterEstado">
                            <option value="">Todos los Estados</option>
                            <option value="Activo">Activos</option>
                            <option value="Inactivo">Inactivos</option>
                            <option value="Licencia">En Licencia</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-secondary w-100" onclick="limpiarFiltros()">
                            <i class="fas fa-undo me-1"></i>Limpiar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tabla -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Listado de Empleados</h5>
                <small class="text-muted" id="resultadoBusqueda"><?= $total_empleados ?> registros</small>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="tablaEmpleados">
                        <thead class="bg-light">
                            <tr>
                                <th>Código</th>
                                <th>Empleado</th>
                                <th>Cargo / Área</th>
                                <th>Contacto</th>
                                <th>Fecha Ingreso</th>
                                <th>Estado</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($empleados) > 0): ?>
                                <?php foreach ($empleados as $emp): 
                                    $nombre_completo = strtolower($emp['primer_nombre'] . ' ' . $emp['Apellidos']);
                                    $cargo_area = strtolower(($emp['cargo_nombre'] ?? '') . ' ' . ($emp['area_nombre'] ?? '') . ' ' . ($emp['depto_nombre'] ?? ''));
                                    $email = strtolower($emp['email'] ?? '');
                                ?>
                                <tr data-depto="<?= htmlspecialchars($emp['depto_nombre']) ?>" 
                                    data-estado="<?= $emp['estado'] ?>"
                                    data-search="<?= htmlspecialchars($nombre_completo . ' ' . $cargo_area . ' ' . $email) ?>">
                                    <td>
                                        <span class="badge bg-light text-dark"><?= htmlspecialchars($emp['codigo_emp']) ?></span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                <?= strtoupper(substr($emp['primer_nombre'], 0, 1)) ?>
                                            </div>
                                            <div>
                                                <strong><?= htmlspecialchars($emp['primer_nombre'] . ' ' . $emp['Apellidos']) ?></strong>
                                                <?php if (!empty($emp['segundo_nombre'])): ?>
                                                <br><small class="text-muted"><?= htmlspecialchars($emp['segundo_nombre']) ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong><?= htmlspecialchars($emp['cargo_nombre'] ?? 'Sin cargo') ?></strong>
                                            <br><small class="text-muted"><?= htmlspecialchars($emp['area_nombre'] ?? '') ?> / <?= htmlspecialchars($emp['depto_nombre'] ?? '') ?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <small>
                                            <i class="fas fa-phone me-1 text-muted"></i><?= htmlspecialchars($emp['telefono_principal']) ?><br>
                                            <?php if (!empty($emp['email'])): ?>
                                            <i class="fas fa-envelope me-1 text-muted"></i><?= htmlspecialchars($emp['email']) ?>
                                            <?php else: ?>
                                            <i class="fas fa-envelope me-1 text-muted"></i><span class="text-muted">N/A</span>
                                            <?php endif; ?>
                                        </small>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($emp['fecha_ingreso'])) ?></td>
                                    <td>
                                        <?php 
                                        $badge = match($emp['estado']) {
                                            'Activo' => 'bg-success',
                                            'Inactivo' => 'bg-secondary',
                                            'Licencia' => 'bg-warning text-dark',
                                            default => 'bg-light text-dark'
                                        };
                                        ?>
                                        <span class="badge <?= $badge ?> rounded-pill"><?= $emp['estado'] ?></span>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group">
                                            <a href="ver_empleado.php?id=<?= $emp['id_empleado'] ?>" class="btn btn-sm btn-outline-info" title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="editar_empleado.php?id=<?= $emp['id_empleado'] ?>" class="btn btn-sm btn-outline-warning" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn btn-sm btn-<?= $emp['estado'] === 'Activo' ? 'outline-danger' : 'outline-success' ?>" 
                                                    title="<?= $emp['estado'] === 'Activo' ? 'Desactivar' : 'Activar' ?>" 
                                                    onclick="confirmarAccion(<?= $emp['id_empleado'] ?>, '<?= addslashes($emp['primer_nombre'] . ' ' . $emp['Apellidos']) ?>', '<?= $emp['estado'] === 'Activo' ? 'desactivar' : 'activar' ?>')">
                                                <i class="fas fa-<?= $emp['estado'] === 'Activo' ? 'ban' : 'check' ?>"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-5">
                                        <i class="fas fa-users fa-3x mb-3 opacity-25"></i>
                                        <p class="mb-3">No hay empleados registrados</p>
                                        <a href="nuevo_empleado.php" class="btn btn-primary btn-sm">
                                            <i class="fas fa-user-plus me-1"></i>Contratar primer empleado
                                        </a>
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

<!-- Modal de Confirmación -->
<div class="modal fade" id="confirmarAccionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-0 pt-3 px-4">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4 pt-0">
                <div class="confirm-icon-container mb-4">
                    <div class="confirm-icon" id="confirmIcon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
                <h4 class="modal-title mb-2 fw-bold" id="confirmTitle">¿Desactivar Empleado?</h4>
                <p class="text-muted mb-4" id="confirmMessage">Estás a punto de desactivar al empleado.<br>Esta acción puede ser revertida más tarde.</p>
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-danger btn-lg rounded-pill" id="confirmAction">
                        <i class="fas fa-ban me-2"></i>Sí, Desactivar
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-lg rounded-pill" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                </div>
                <div class="mt-4 pt-3 border-top">
                    <small class="text-muted"><i class="fas fa-info-circle me-1"></i>El empleado no podrá acceder al sistema</small>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
#confirmarAccionModal .modal-content { border-radius: 20px; overflow: hidden; animation: modalSlideIn 0.3s ease; }
@keyframes modalSlideIn { from { opacity: 0; transform: translateY(-50px) scale(0.9); } to { opacity: 1; transform: translateY(0) scale(1); } }
#confirmarAccionModal .confirm-icon { width: 100px; height: 100px; background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; animation: shakeWarning 0.5s ease; box-shadow: 0 10px 30px rgba(255, 193, 7, 0.3); }
#confirmarAccionModal .confirm-icon i { font-size: 3rem; color: white; }
@keyframes shakeWarning { 0%, 100% { transform: translateX(0) rotate(0deg); } 25% { transform: translateX(-10px) rotate(-5deg); } 50% { transform: translateX(10px) rotate(5deg); } 75% { transform: translateX(-10px) rotate(-5deg); } }
#confirmarAccionModal .modal-title { color: #1e293b; font-size: 1.5rem; }
#confirmarAccionModal .btn-danger { background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); border: none; font-weight: 600; padding: 12px; transition: all 0.3s ease; }
#confirmarAccionModal .btn-danger:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(220, 53, 69, 0.4); }
#confirmarAccionModal .confirm-icon.success { background: linear-gradient(135deg, #28a745 0%, #218838 100%); animation: scaleIn 0.5s ease; }
#confirmarAccionModal .confirm-icon.danger { background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); animation: scaleIn 0.5s ease; }
@keyframes scaleIn { from { transform: scale(0); opacity: 0; } 50% { transform: scale(1.2); } to { transform: scale(1); opacity: 1; } }
</style>

<script>
let callbackAccion = null;

function confirmarAccion(id, nombre, tipo = 'desactivar') {
    const modal = new bootstrap.Modal(document.getElementById('confirmarAccionModal'));
    const icon = document.getElementById('confirmIcon');
    const title = document.getElementById('confirmTitle');
    const message = document.getElementById('confirmMessage');
    const btnConfirm = document.getElementById('confirmAction');
    
    icon.className = 'confirm-icon';
    
    if (tipo === 'desactivar') {
        icon.innerHTML = '<i class="fas fa-user-slash"></i>';
        icon.classList.add('danger');
        title.innerHTML = '¿Desactivar Empleado?';
        message.innerHTML = `Estás a punto de desactivar al empleado <strong>${nombre}</strong>.<br>Esta acción puede ser revertida más tarde.`;
        btnConfirm.innerHTML = '<i class="fas fa-ban me-2"></i>Sí, Desactivar';
        btnConfirm.className = 'btn btn-danger btn-lg rounded-pill';
        callbackAccion = function() { cambiarEstadoEmpleado(id, nombre, 'Inactivo'); };
    } else if (tipo === 'activar') {
        icon.innerHTML = '<i class="fas fa-user-check"></i>';
        icon.classList.add('success');
        title.innerHTML = '¿Activar Empleado?';
        message.innerHTML = `Estás a punto de reactivar al empleado <strong>${nombre}</strong>.<br>El empleado podrá acceder al sistema nuevamente.`;
        btnConfirm.innerHTML = '<i class="fas fa-check me-2"></i>Sí, Activar';
        btnConfirm.className = 'btn btn-success btn-lg rounded-pill';
        callbackAccion = function() { cambiarEstadoEmpleado(id, nombre, 'Activo'); };
    }
    
    btnConfirm.onclick = function() { if (callbackAccion) { callbackAccion(); modal.hide(); } };
    modal.show();
}

function cambiarEstadoEmpleado(id, nombre, nuevoEstado) {
    const btn = document.getElementById('confirmAction');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Procesando...';
    
    fetch('../../api/cambiar_estado_empleado.php', {
        method: 'POST',
        headers: { 
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            id_empleado: id,
            estado: nuevoEstado
        })
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => {
                throw new Error(`HTTP ${response.status}: ${text}`);
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showToast('✅ ' + data.message, 'success');
            setTimeout(() => { window.location.reload(); }, 1000);
        } else {
            throw new Error(data.message || 'Error desconocido');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('❌ Error de conexión: ' + error.message, 'danger');
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
}

function showToast(message, type = 'success') {
    const toastContainer = document.getElementById('toastContainer') || createToastContainer();
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type === 'error' || type === 'danger' ? 'danger' : (type === 'warning' ? 'warning' : 'success')} border-0 mb-2`;
    toast.innerHTML = `<div class="d-flex"><div class="toast-body">${message}</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>`;
    toastContainer.appendChild(toast);
    new bootstrap.Toast(toast, { delay: 3000 }).show();
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

// Filtros
document.getElementById('searchEmpleados')?.addEventListener('input', aplicarFiltros);
document.getElementById('filterDepartamento')?.addEventListener('change', aplicarFiltros);
document.getElementById('filterEstado')?.addEventListener('change', aplicarFiltros);

function aplicarFiltros() {
    const search = document.getElementById('searchEmpleados').value.toLowerCase();
    const depto = document.getElementById('filterDepartamento').value.toLowerCase();
    const estado = document.getElementById('filterEstado').value.toLowerCase();
    let visible = 0;
    
    document.querySelectorAll('#tablaEmpleados tbody tr').forEach(row => {
        const rowSearch = row.dataset.search?.toLowerCase() || '';
        const rowDepto = row.dataset.depto?.toLowerCase() || '';
        const rowEstado = row.dataset.estado?.toLowerCase() || '';
        const matchSearch = !search || rowSearch.includes(search);
        const matchDepto = !depto || rowDepto.includes(depto);
        const matchEstado = !estado || rowEstado === estado;
        if (matchSearch && matchDepto && matchEstado) { row.style.display = ''; visible++; } else { row.style.display = 'none'; }
    });
    document.getElementById('resultadoBusqueda').textContent = `${visible} registros`;
}

function limpiarFiltros() {
    document.getElementById('searchEmpleados').value = '';
    document.getElementById('filterDepartamento').value = '';
    document.getElementById('filterEstado').value = '';
    aplicarFiltros();
}
</script>

<?php include '../../includes/footer.php'; ?>