<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION['id_usuario']) || !isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$db = getDB();
$id_empleado = $_GET['id'];

$stmt = $db->prepare("SELECT e.*, p.*, c.nombre as cargo_nombre, a.nombre as area_nombre FROM empleado e INNER JOIN persona p ON e.id_persona = p.id_persona LEFT JOIN cargo c ON e.id_cargo = c.id_cargo LEFT JOIN area a ON e.id_area = a.id_area WHERE e.id_empleado = ?");
$stmt->execute([$id_empleado]);
$data = $stmt->fetch();

if (!$data) { header("Location: index.php"); exit; }

$empleado = $data;
$persona = $data;

$departamentos = $db->query("SELECT * FROM departamento ORDER BY nombre")->fetchAll();
$areas = $db->query("SELECT * FROM area ORDER BY nombre")->fetchAll();
$cargos = $db->query("SELECT * FROM cargo ORDER BY nombre")->fetchAll();
$unidades = $db->query("SELECT * FROM unidad_operativa WHERE estado = 'Activo' ORDER BY nombre")->fetchAll();

$titulo_pagina = 'Editar Empleado - RRHH';
include '../../includes/header.php';
include '../../includes/sidebar.php';
?>

<div class="main-wrapper">
    <div class="container-fluid py-4">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0"><i class="fas fa-user-edit me-2 text-warning"></i>Editar Empleado</h2>
                <small class="text-muted">Actualizar información del empleado</small>
            </div>
            <a href="index.php" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Volver</a>
        </div>
        
        <div id="alertContainer"></div>
        
        <form id="formEditarEmpleado" novalidate>
            <input type="hidden" name="accion" value="actualizar">
            <input type="hidden" name="id_empleado" value="<?= $id_empleado ?>">
            
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3"><h5 class="mb-0"><i class="fas fa-id-card me-2"></i>Datos Personales</h5></div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">Tipo de Persona</label>
                                    <select class="form-select" name="persona[tipo_persona]" required>
                                        <option value="Nacional" <?= $persona['tipo_persona'] === 'Nacional' ? 'selected' : '' ?>>Nacional</option>
                                        <option value="Extranjero" <?= $persona['tipo_persona'] === 'Extranjero' ? 'selected' : '' ?>>Extranjero</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Tipo Identificación</label>
                                    <select class="form-select" name="persona[tipo_identificacion]" required>
                                        <option value="Cedula" <?= $persona['tipo_identificacion'] === 'Cedula' ? 'selected' : '' ?>>Cédula</option>
                                        <option value="Pasaporte" <?= $persona['tipo_identificacion'] === 'Pasaporte' ? 'selected' : '' ?>>Pasaporte</option>
                                        <option value="RUC" <?= $persona['tipo_identificacion'] === 'RUC' ? 'selected' : '' ?>>RUC</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Número Identificación</label>
                                    <input type="text" class="form-control" name="persona[numero_identificacion]" value="<?= htmlspecialchars($persona['numero_identificacion']) ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Primer Nombre</label>
                                    <input type="text" class="form-control" name="persona[primer_nombre]" value="<?= htmlspecialchars($persona['primer_nombre']) ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Segundo Nombre</label>
                                    <input type="text" class="form-control" name="persona[segundo_nombre]" value="<?= htmlspecialchars($persona['segundo_nombre'] ?? '') ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Apellidos</label>
                                    <input type="text" class="form-control" name="persona[Apellidos]" value="<?= htmlspecialchars($persona['Apellidos']) ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Teléfono</label>
                                    <input type="tel" class="form-control" name="persona[telefono_principal]" value="<?= htmlspecialchars($persona['telefono_principal']) ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="persona[email]" value="<?= htmlspecialchars($persona['email'] ?? '') ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Fecha Nacimiento</label>
                                    <input type="date" class="form-control" name="persona[fecha_nacimiento]" value="<?= $persona['fecha_nacimiento'] ?>">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Dirección</label>
                                    <textarea class="form-control" name="persona[direccion]" rows="2"><?= htmlspecialchars($persona['direccion'] ?? '') ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3"><h5 class="mb-0"><i class="fas fa-briefcase me-2"></i>Datos Laborales</h5></div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Unidad Operativa</label>
                                    <select class="form-select" name="empleado[id_unidad]" required>
                                        <?php foreach ($unidades as $uni): ?>
                                        <option value="<?= $uni['id_unidad'] ?>" <?= $empleado['id_unidad'] == $uni['id_unidad'] ? 'selected' : '' ?>><?= htmlspecialchars($uni['nombre']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Cargo</label>
                                    <select class="form-select" name="empleado[id_cargo]" required>
                                        <option value="">Seleccionar...</option>
                                        <?php foreach ($cargos as $cargo): ?>
                                        <option value="<?= $cargo['id_cargo'] ?>" <?= $empleado['id_cargo'] == $cargo['id_cargo'] ? 'selected' : '' ?>><?= htmlspecialchars($cargo['nombre']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Fecha Ingreso</label>
                                    <input type="date" class="form-control" name="empleado[fecha_ingreso]" value="<?= $empleado['fecha_ingreso'] ?>" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Categoría</label>
                                    <select class="form-select" name="empleado[tipo_categoria]" required>
                                        <option value="Administrativo" <?= $empleado['tipo_categoria'] === 'Administrativo' ? 'selected' : '' ?>>Administrativo</option>
                                        <option value="Operativo" <?= $empleado['tipo_categoria'] === 'Operativo' ? 'selected' : '' ?>>Operativo</option>
                                        <option value="Gerencial" <?= $empleado['tipo_categoria'] === 'Gerencial' ? 'selected' : '' ?>>Gerencial</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Estado</label>
                                    <select class="form-select" name="empleado[estado]" required>
                                        <option value="Activo" <?= $empleado['estado'] === 'Activo' ? 'selected' : '' ?>>Activo</option>
                                        <option value="Inactivo" <?= $empleado['estado'] === 'Inactivo' ? 'selected' : '' ?>>Inactivo</option>
                                        <option value="Licencia" <?= $empleado['estado'] === 'Licencia' ? 'selected' : '' ?>>Licencia</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-end gap-3 mt-4">
                <a href="index.php" class="btn btn-secondary"><i class="fas fa-times me-2"></i>Cancelar</a>
                <button type="submit" class="btn btn-warning btn-lg" id="btnSubmit"><i class="fas fa-save me-2"></i>Guardar Cambios</button>
            </div>
        </form>
        
    </div>
</div>

<script>
document.getElementById('formEditarEmpleado')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const form = this;
    const btn = document.getElementById('btnSubmit');
    const alertContainer = document.getElementById('alertContainer');
    const originalBtnText = btn.innerHTML;
    
    if (!form.checkValidity()) {
        form.reportValidity();
        showAlert('⚠️ Complete los campos obligatorios', 'warning');
        return;
    }
    
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Guardando...';
    alertContainer.innerHTML = '';
    
    try {
        const formData = new FormData(form);
        const response = await fetch('../../api/guardar_empleado.php', { method: 'POST', body: formData });
        const text = await response.text();
        
        if (!text) throw new Error('La respuesta del servidor está vacía');
        
        let result;
        try { result = JSON.parse(text); } catch (e) { console.error('JSON inválido:', text); throw new Error('Respuesta inválida del servidor'); }
        
        if (result.success) {
            showAlert('✅ ' + result.message, 'success');
            setTimeout(() => { window.location.href = 'index.php'; }, 1500);
        } else {
            throw new Error(result.message || 'Error al guardar');
        }
    } catch (error) {
        console.error('Error:', error);
        showAlert('❌ ' + error.message, 'danger');
        btn.disabled = false;
        btn.innerHTML = originalBtnText;
    }
});

function showAlert(message, type) {
    const container = document.getElementById('alertContainer');
    const alertClass = type === 'success' ? 'alert-success' : (type === 'warning' ? 'alert-warning' : 'alert-danger');
    const icon = type === 'success' ? 'check-circle' : (type === 'warning' ? 'exclamation-triangle' : 'times-circle');
    container.innerHTML = `<div class="alert ${alertClass} alert-dismissible fade show" role="alert"><i class="fas fa-${icon} me-2"></i>${message}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>`;
}
</script>

<?php include '../../includes/footer.php'; ?>