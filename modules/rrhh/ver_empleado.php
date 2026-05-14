<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION['id_usuario']) || !isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$db = getDB();
$id_empleado = $_GET['id'];

$stmt = $db->prepare("SELECT e.*, p.*, c.nombre as cargo_nombre, a.nombre as area_nombre, d.nombre as depto_nombre, u.nombre as unidad_nombre FROM empleado e INNER JOIN persona p ON e.id_persona = p.id_persona LEFT JOIN cargo c ON e.id_cargo = c.id_cargo LEFT JOIN area a ON e.id_area = a.id_area LEFT JOIN departamento d ON a.id_depto = d.id_depto LEFT JOIN unidad_operativa u ON e.id_unidad = u.id_unidad WHERE e.id_empleado = ?");
$stmt->execute([$id_empleado]);
$emp = $stmt->fetch();

if (!$emp) { header("Location: index.php"); exit; }

$titulo_pagina = 'Ver Empleado - RRHH';
include '../../includes/header.php';
include '../../includes/sidebar.php';
?>

<div class="main-wrapper">
    <div class="container-fluid py-4">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0"><i class="fas fa-user me-2 text-primary"></i>Detalles del Empleado</h2>
                <small class="text-muted">Información completa del empleado</small>
            </div>
            <div class="d-flex gap-2">
                <a href="editar_empleado.php?id=<?= $emp['id_empleado'] ?>" class="btn btn-warning"><i class="fas fa-edit me-2"></i>Editar</a>
                <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Volver</a>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3"><h5 class="mb-0"><i class="fas fa-id-card me-2"></i>Información Personal</h5></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12 text-center mb-3">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                                    <span class="fs-1 text-primary"><?= strtoupper(substr($emp['primer_nombre'], 0, 1)) ?></span>
                                </div>
                                <h4 class="mt-3"><?= htmlspecialchars($emp['primer_nombre'] . ' ' . $emp['Apellidos']) ?></h4>
                                <span class="badge bg-<?= $emp['estado'] === 'Activo' ? 'success' : 'secondary' ?> rounded-pill"><?= $emp['estado'] ?></span>
                            </div>
                            <div class="col-md-6"><label class="text-muted small">Código</label><p class="mb-0 fw-bold"><?= htmlspecialchars($emp['codigo_emp']) ?></p></div>
                            <div class="col-md-6"><label class="text-muted small">Identificación</label><p class="mb-0 fw-bold"><?= htmlspecialchars($emp['tipo_identificacion'] . ' ' . $emp['numero_identificacion']) ?></p></div>
                            <div class="col-md-6"><label class="text-muted small">Fecha Nacimiento</label><p class="mb-0"><?= $emp['fecha_nacimiento'] ? date('d/m/Y', strtotime($emp['fecha_nacimiento'])) : 'N/A' ?></p></div>
                            <div class="col-md-6"><label class="text-muted small">Sexo</label><p class="mb-0"><?= htmlspecialchars($emp['sexo'] ?? 'N/A') ?></p></div>
                            <div class="col-md-6"><label class="text-muted small">Estado Civil</label><p class="mb-0"><?= htmlspecialchars($emp['estado_civil'] ?? 'N/A') ?></p></div>
                            <div class="col-md-6"><label class="text-muted small">Tipo de Sangre</label><p class="mb-0"><?= htmlspecialchars($emp['Tipo_sangre'] ?? 'N/A') ?></p></div>
                            <div class="col-12"><label class="text-muted small">Dirección</label><p class="mb-0"><?= htmlspecialchars($emp['direccion'] ?? 'N/A') ?></p></div>
                            <div class="col-md-6"><label class="text-muted small">Teléfono</label><p class="mb-0"><i class="fas fa-phone me-1"></i><?= htmlspecialchars($emp['telefono_principal']) ?></p></div>
                            <div class="col-md-6"><label class="text-muted small">Email</label><p class="mb-0"><i class="fas fa-envelope me-1"></i><?= htmlspecialchars($emp['email'] ?? 'N/A') ?></p></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3"><h5 class="mb-0"><i class="fas fa-briefcase me-2"></i>Información Laboral</h5></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6"><label class="text-muted small">Unidad</label><p class="mb-0 fw-bold"><?= htmlspecialchars($emp['unidad_nombre']) ?></p></div>
                            <div class="col-md-6"><label class="text-muted small">Departamento</label><p class="mb-0 fw-bold"><?= htmlspecialchars($emp['depto_nombre']) ?></p></div>
                            <div class="col-md-6"><label class="text-muted small">Área</label><p class="mb-0"><?= htmlspecialchars($emp['area_nombre']) ?></p></div>
                            <div class="col-md-6"><label class="text-muted small">Cargo</label><p class="mb-0 fw-bold"><?= htmlspecialchars($emp['cargo_nombre']) ?></p></div>
                            <div class="col-md-6"><label class="text-muted small">Fecha Ingreso</label><p class="mb-0"><?= date('d/m/Y', strtotime($emp['fecha_ingreso'])) ?></p></div>
                            <div class="col-md-6"><label class="text-muted small">Categoría</label><p class="mb-0"><span class="badge bg-info"><?= htmlspecialchars($emp['tipo_categoria']) ?></span></p></div>
                            <div class="col-md-6"><label class="text-muted small">Aplica Vacaciones</label><p class="mb-0"><?= $emp['aplica_vacaciones'] ? '<span class="text-success"><i class="fas fa-check"></i> Sí</span>' : '<span class="text-danger"><i class="fas fa-times"></i> No</span>' ?></p></div>
                            <div class="col-md-6"><label class="text-muted small">Estado</label><p class="mb-0"><span class="badge bg-<?= $emp['estado'] === 'Activo' ? 'success' : ($emp['estado'] === 'Licencia' ? 'warning' : 'secondary') ?> rounded-pill"><?= $emp['estado'] ?></span></p></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>

<?php include '../../includes/footer.php'; ?>