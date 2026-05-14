<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../../login.php");
    exit;
}

$db = getDB();
$departamentos = $db->query("SELECT id_depto, nombre FROM departamento ORDER BY nombre ASC")->fetchAll();
$areas = $db->query("SELECT id_area, id_depto, nombre FROM area ORDER BY nombre ASC")->fetchAll();
$cargos = $db->query("SELECT id_cargo, id_area, nombre FROM cargo ORDER BY nombre ASC")->fetchAll();
$unidades = $db->query("SELECT id_unidad, nombre FROM unidad_operativa WHERE estado = 'Activo' ORDER BY nombre ASC")->fetchAll();

$titulo_pagina = 'Contratar Nuevo Empleado - RRHH';
include '../../includes/header.php';
include '../../includes/sidebar.php';
?>

<div class="main-wrapper">
    <div class="container-fluid py-4">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0">
                    <i class="fas fa-user-plus me-2 text-success"></i>Contratar Nuevo Empleado
                </h2>
                <small class="text-muted">Registra los datos del nuevo miembro del equipo</small>
            </div>
            <a href="index.php" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Volver al Listado
            </a>
        </div>
        
        <div id="alertContainer"></div>
        
        <form id="formNuevoEmpleado" novalidate>
            <div class="row g-4">
                
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0"><i class="fas fa-id-card me-2"></i>Datos Personales</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">Tipo de Persona *</label>
                                    <select class="form-select" name="persona[tipo_persona]" required>
                                        <option value="Nacional" selected>Nacional</option>
                                        <option value="Extranjero">Extranjero</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Tipo de Identificación *</label>
                                    <select class="form-select" name="persona[tipo_identificacion]" required>
                                        <option value="Cedula" selected>Cédula</option>
                                        <option value="Pasaporte">Pasaporte</option>
                                        <option value="RUC">RUC</option>
                                        <option value="DNI">DNI</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Número de Identificación *</label>
                                    <input type="text" class="form-control" name="persona[numero_identificacion]" required placeholder="Ej: 001-010595-1000A">
                                </div>
                                
                                <div class="col-md-4">
                                    <label class="form-label">Primer Nombre *</label>
                                    <input type="text" class="form-control" name="persona[primer_nombre]" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Segundo Nombre</label>
                                    <input type="text" class="form-control" name="persona[segundo_nombre]">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Apellidos *</label>
                                    <input type="text" class="form-control" name="persona[Apellidos]" required>
                                </div>
                                
                                <div class="col-md-3">
                                    <label class="form-label">Fecha de Nacimiento</label>
                                    <input type="date" class="form-control" name="persona[fecha_nacimiento]">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Sexo</label>
                                    <select class="form-select" name="persona[sexo]">
                                        <option value="">Seleccionar</option>
                                        <option value="Masculino">Masculino</option>
                                        <option value="Femenino">Femenino</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Estado Civil</label>
                                    <select class="form-select" name="persona[estado_civil]">
                                        <option value="Soltero" selected>Soltero/a</option>
                                        <option value="Casado">Casado/a</option>
                                        <option value="Union_Libre">Unión Libre</option>
                                        <option value="Divorciado">Divorciado/a</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Tipo de Sangre</label>
                                    <select class="form-select" name="persona[Tipo_sangre]">
                                        <option value="">Seleccionar</option>
                                        <option value="A+">A+</option><option value="A-">A-</option>
                                        <option value="B+">B+</option><option value="B-">B-</option>
                                        <option value="O+">O+</option><option value="O-">O-</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-4">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="persona[email]" placeholder="correo@ejemplo.com">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Teléfono Principal *</label>
                                    <input type="tel" class="form-control" name="persona[telefono_principal]" required placeholder="+505 8888-9999">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Teléfono Emergencia</label>
                                    <input type="tel" class="form-control" name="persona[telefono_emergencia]" placeholder="+505 8888-9999">
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label">Dirección *</label>
                                    <textarea class="form-control" name="persona[direccion]" rows="2" required placeholder="Dirección completa"></textarea>
                                </div>
                                
                                <div class="col-md-4">
                                    <label class="form-label">Ciudad</label>
                                    <input type="text" class="form-control" name="persona[ciudad]" value="Managua">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Departamento</label>
                                    <input type="text" class="form-control" name="persona[departamento_estado]" value="Managua">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">País</label>
                                    <select class="form-select" name="persona[pais]">
                                        <option value="NI" selected>Nicaragua</option>
                                        <option value="CR">Costa Rica</option>
                                        <option value="SV">El Salvador</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0"><i class="fas fa-briefcase me-2"></i>Datos Laborales</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Unidad Operativa *</label>
                                    <select class="form-select" name="empleado[id_unidad]" required>
                                        <option value="">Seleccionar...</option>
                                        <?php foreach ($unidades as $uni): ?>
                                        <option value="<?= $uni['id_unidad'] ?>" <?= $uni['id_unidad'] == 1 ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($uni['nombre']) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Departamento *</label>
                                    <select class="form-select" name="empleado[id_depto]" id="selectDepto" required>
                                        <option value="">Seleccionar...</option>
                                        <?php foreach ($departamentos as $depto): ?>
                                        <option value="<?= $depto['id_depto'] ?>"><?= htmlspecialchars($depto['nombre']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Área *</label>
                                    <select class="form-select" name="empleado[id_area]" id="selectArea" required>
                                        <option value="">Primero selecciona departamento...</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Cargo *</label>
                                    <select class="form-select" name="empleado[id_cargo]" id="selectCargo" required>
                                        <option value="">Primero selecciona área...</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Fecha de Ingreso *</label>
                                    <input type="date" class="form-control" name="empleado[fecha_ingreso]" value="<?= date('Y-m-d') ?>" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Categoría *</label>
                                    <select class="form-select" name="empleado[tipo_categoria]" required>
                                        <option value="Administrativo" selected>Administrativo</option>
                                        <option value="Operativo">Operativo</option>
                                        <option value="Gerencial">Gerencial</option>
                                        <option value="Temporario">Temporal</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Estado Inicial</label>
                                    <select class="form-select" name="empleado[estado]">
                                        <option value="Activo" selected>Activo</option>
                                        <option value="Inactivo">Inactivo</option>
                                        <option value="Licencia">En Licencia</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0"><i class="fas fa-user-lock me-2"></i>Acceso al Sistema</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="crearUsuario" name="crear_usuario" value="1">
                                <label class="form-check-label" for="crearUsuario">Crear cuenta de usuario</label>
                            </div>
                            <div id="seccionUsuario" style="display: none;">
                                <div class="mb-3">
                                    <label class="form-label">Nombre de Usuario</label>
                                    <input type="text" class="form-control" name="usuario[username]" placeholder="ej: carlos.mendoza">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Contraseña</label>
                                    <input type="password" class="form-control" name="usuario[contraseña]" placeholder="••••••••">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Rol</label>
                                    <select class="form-select" name="usuario[id_rol]">
                                        <option value="2" selected>Cajero</option>
                                        <option value="3">Cocinero</option>
                                        <option value="4">Mesero</option>
                                        <option value="1">Administrador</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            
            <div class="d-flex justify-content-end gap-3 mt-4">
                <a href="index.php" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i>Cancelar
                </a>
                <button type="submit" class="btn btn-success btn-lg" id="btnSubmit">
                    <i class="fas fa-save me-2"></i>Guardar Empleado
                </button>
            </div>
        </form>
        
    </div>
</div>

<script>
const areasData = <?= json_encode($areas) ?>;
const cargosData = <?= json_encode($cargos) ?>;

document.getElementById('selectDepto')?.addEventListener('change', function() {
    const idDepto = this.value;
    const selectArea = document.getElementById('selectArea');
    selectArea.innerHTML = '<option value="">Seleccionar...</option>';
    document.getElementById('selectCargo').innerHTML = '<option value="">Primero selecciona área...</option>';
    if (idDepto) {
        const areasFiltradas = areasData.filter(a => a.id_depto == idDepto);
        areasFiltradas.forEach(area => { selectArea.innerHTML += `<option value="${area.id_area}">${area.nombre}</option>`; });
    }
});

document.getElementById('selectArea')?.addEventListener('change', function() {
    const idArea = this.value;
    const selectCargo = document.getElementById('selectCargo');
    selectCargo.innerHTML = '<option value="">Seleccionar...</option>';
    if (idArea) {
        const cargosFiltrados = cargosData.filter(c => c.id_area == idArea);
        cargosFiltrados.forEach(cargo => { selectCargo.innerHTML += `<option value="${cargo.id_cargo}">${cargo.nombre}</option>`; });
    }
});

document.getElementById('crearUsuario')?.addEventListener('change', function() {
    document.getElementById('seccionUsuario').style.display = this.checked ? 'block' : 'none';
});

document.getElementById('formNuevoEmpleado')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const form = this;
    const btn = document.getElementById('btnSubmit');
    const alertContainer = document.getElementById('alertContainer');
    const originalBtnText = btn.innerHTML;
    
    if (!form.checkValidity()) {
        form.reportValidity();
        showAlert('⚠️ Por favor complete los campos obligatorios (*)', 'warning');
        return;
    }
    
    if (document.getElementById('crearUsuario').checked) {
        const user = document.querySelector('input[name="usuario[username]"]')?.value;
        const pass = document.querySelector('input[name="usuario[contraseña]"]')?.value;
        if (!user || !pass) {
            showAlert('⚠️ Debe completar usuario y contraseña', 'warning');
            return;
        }
    }
    
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Guardando...';
    alertContainer.innerHTML = '';
    
    try {
        const formData = new FormData(form);
        formData.append('accion', 'crear');
        
        const response = await fetch('../../api/guardar_empleado.php', { method: 'POST', body: formData });
        const text = await response.text();
        
        if (!text) throw new Error('La respuesta del servidor está vacía');
        
        let result;
        try { result = JSON.parse(text); } catch (e) { console.error('JSON inválido:', text); throw new Error('Respuesta inválida del servidor'); }
        
        if (result.success) {
            showAlert('✅ ' + result.message, 'success');
            setTimeout(() => { window.location.href = 'index.php'; }, 1500);
        } else {
            throw new Error(result.message || 'Error desconocido');
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

document.querySelectorAll('input[type="tel"]').forEach(input => {
    input.addEventListener('input', function(e) { this.value = this.value.replace(/[^\d+\-\s]/g, ''); });
});
</script>

<?php include '../../includes/footer.php'; ?>