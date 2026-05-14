<?php
require_once '../../config/database.php';
$titulo_pagina = 'Nueva Persona';
include '../../includes/header.php';
include '../../includes/navbar.php';
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0"><i class="fas fa-user-plus me-2"></i>Registrar Nueva Persona</h4>
                </div>
                <div class="card-body">
                    <form action="guardar.php" method="POST" id="form-persona">
                        <input type="hidden" name="action" value="crear">
                        
                        <h5 class="text-primary mb-3">Datos de Identificación</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label">Tipo de Persona *</label>
                                <select name="tipo_persona" class="form-select" required>
                                    <option value="Natural">Natural</option>
                                    <option value="Juridica">Jurídica</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tipo de Identificación *</label>
                                <select name="tipo_identificacion" class="form-select" required>
                                    <option value="Cedula">Cédula</option>
                                    <option value="Pasaporte">Pasaporte</option>
                                    <option value="RUC">RUC</option>
                                    <option value="DNI">DNI</option>
                                    <option value="Residencia">Residencia</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Número *</label>
                                <input type="text" name="numero_identificacion" class="form-control" required 
                                       placeholder="Ej: 001-010595-1000A" maxlength="20">
                            </div>
                        </div>
                        
                        <h5 class="text-primary mb-3">Datos Personales</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Primer Nombre *</label>
                                <input type="text" name="primer_nombre" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Segundo Nombre</label>
                                <input type="text" name="segundo_nombre" class="form-control">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Apellidos *</label>
                                <input type="text" name="Apellidos" class="form-control" required 
                                       placeholder="Apellido paterno y materno">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Fecha de Nacimiento</label>
                                <input type="date" name="fecha_nacimiento" class="form-control" max="<?= date('Y-m-d') ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Sexo</label>
                                <select name="sexo" class="form-select">
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Estado Civil</label>
                                <select name="estado_civil" class="form-select">
                                    <option value="Soltero">Soltero/a</option>
                                    <option value="Casado">Casado/a</option>
                                    <option value="Union_Libre">Unión Libre</option>
                                    <option value="Divorciado">Divorciado/a</option>
                                    <option value="Viudo">Viudo/a</option>
                                </select>
                            </div>
                        </div>
                        
                        <h5 class="text-primary mb-3">Contacto</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Teléfono Principal *</label>
                                <input type="tel" name="telefono_principal" class="form-control" required 
                                       placeholder="+505 8888-9999">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Teléfono Emergencia</label>
                                <input type="tel" name="telefono_emergencia" class="form-control">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Dirección *</label>
                                <textarea name="direccion" class="form-control" rows="2" required></textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Ciudad</label>
                                <input type="text" name="ciudad" class="form-control" value="Managua">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Departamento</label>
                                <input type="text" name="departamento_estado" class="form-control" value="Managua">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">País</label>
                                <select name="pais" class="form-select">
                                    <option value="NI" selected>Nicaragua</option>
                                    <option value="CR">Costa Rica</option>
                                    <option value="SV">El Salvador</option>
                                    <option value="GT">Guatemala</option>
                                    <option value="HN">Honduras</option>
                                </select>
                            </div>
                        </div>
                        
                        <h5 class="text-primary mb-3">Salud (Opcional)</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Enfermedades / Alergias</label>
                                <textarea name="Enfermedades" class="form-control" rows="2" 
                                          placeholder="Ej: Alergia a mariscos, Diabetes..."></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tipo de Sangre</label>
                                <select name="Tipo_sangre" class="form-select">
                                    <option value="">Seleccionar...</option>
                                    <option value="A+">A+</option>
                                    <option value="A-">A-</option>
                                    <option value="B+">B+</option>
                                    <option value="B-">B-</option>
                                    <option value="AB+">AB+</option>
                                    <option value="AB-">AB-</option>
                                    <option value="O+">O+</option>
                                    <option value="O-">O-</option>
                                </select>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <div class="d-flex justify-content-between">
                            <a href="index.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Guardar Persona
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('form-persona').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const btn = this.querySelector('button[type="submit"]');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Guardando...';
    
    const formData = new FormData(this);
    
    try {
        const response = await fetch('guardar.php', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.exito) {
            showToast('Persona registrada correctamente');
            setTimeout(() => {
                window.location.href = 'index.php';
            }, 1500);
        } else {
            showToast(data.error || 'Error al guardar', 'error');
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    } catch (error) {
        showToast('Error de conexión: ' + error.message, 'error');
        btn.disabled = false;
        btn.innerHTML = originalText;
    }
});
</script>

<?php include '../../includes/footer.php'; ?>