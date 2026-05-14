<?php
/**
 * NicaFood ERP - Checkout / Finalizar Pedido
 * Archivo: checkout.php
 * 
 * Funcionalidades:
 * - Formulario completo con todos los campos de la tabla persona
 * - Sección de envío (checkbox sí/no con detalles)
 * - Selección de empleado que despacha
 * - Resumen del pedido en tiempo real
 * - Validación de campos obligatorios
 * - Integración con api/procesar_pedido_completo.php
 */

session_start();
require_once 'config/database.php';

// Verificar sesión (opcional: descomentar si requieres login)
// if (!isset($_SESSION['id_usuario'])) {
//     header("Location: login.php");
//     exit;
// }

$db = getDB();

// Obtener empleados activos para el selector
$empleados = $db->query("
    SELECT e.id_empleado, p.primer_nombre, p.Apellidos, c.nombre as cargo 
    FROM empleado e 
    INNER JOIN persona p ON e.id_persona = p.id_persona 
    INNER JOIN cargo c ON e.id_cargo = c.id_cargo 
    WHERE e.estado = 'Activo' 
    ORDER BY p.primer_nombre, p.Apellidos
")->fetchAll();

// Obtener zonas de envío para el selector
$zonas_envio = $db->query("
    SELECT id_tarifa, nombre_zona, costo_envio, tiempo_estimado_min 
    FROM tarifa_zona 
    WHERE activo = 1 
    ORDER BY nombre_zona
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Pedido - NicaFood ERP</title>
    
    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #0b3185;
            --primary-dark: #082566;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --light: #f8f9fa;
            --dark: #1e293b;
            --shadow: 0 4px 20px rgba(0,0,0,0.08);
            --radius: 12px;
        }
        
        body {
            background: var(--light);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: var(--dark);
            line-height: 1.6;
        }
        
        .required::after {
            content: " *";
            color: var(--danger);
            font-weight: 600;
        }
        
        .section-card {
            border: none;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            margin-bottom: 24px;
            transition: transform 0.2s ease;
        }
        
        .section-card:hover {
            transform: translateY(-2px);
        }
        
        .section-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            border-radius: var(--radius) var(--radius) 0 0 !important;
            padding: 16px 20px;
        }
        
        .section-header i {
            margin-right: 8px;
            font-size: 1.1rem;
        }
        
        .section-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 1.1rem;
        }
        
        .form-label {
            font-weight: 500;
            font-size: 0.9rem;
            color: var(--dark);
            margin-bottom: 6px;
        }
        
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #d1d5db;
            padding: 10px 14px;
            font-size: 0.95rem;
            transition: border-color 0.2s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(11, 49, 133, 0.1);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border: none;
            padding: 12px 24px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(11, 49, 133, 0.4);
        }
        
        .btn-success {
            background: linear-gradient(135deg, var(--success) 0%, #059669 100%);
            border: none;
            padding: 12px 24px;
            border-radius: 50px;
            font-weight: 600;
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
        }
        
        .cart-summary {
            background: white;
            border-radius: var(--radius);
            padding: 20px;
            box-shadow: var(--shadow);
        }
        
        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
        }
        
        .cart-item:last-child {
            border-bottom: none;
        }
        
        .cart-item-name {
            font-weight: 600;
            color: var(--dark);
        }
        
        .cart-item-meta {
            font-size: 0.85rem;
            color: #64748b;
        }
        
        .cart-item-price {
            font-weight: 700;
            color: var(--primary);
        }
        
        .cart-total {
            background: #f8fafc;
            border-radius: 10px;
            padding: 16px 20px;
            margin-top: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 1.2rem;
        }
        
        .cart-total strong {
            color: var(--primary);
            font-size: 1.4rem;
        }
        
        /* Sección de envío */
        #seccionEnvio {
            display: none;
            background: #f8fafc;
            border-radius: 10px;
            padding: 20px;
            margin-top: 16px;
            border-left: 4px solid var(--warning);
        }
        
        #seccionEnvio.active {
            display: block;
            animation: slideDown 0.3s ease;
        }
        
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Toast notifications */
        .toast {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
        }
        
        .toast.bg-success { 
            background: linear-gradient(135deg, var(--success), #059669) !important; 
        }
        
        .toast.bg-danger { 
            background: linear-gradient(135deg, var(--danger), #dc2626) !important; 
        }
        
        .toast .toast-header {
            background: transparent;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            color: white;
            padding: 12px 15px;
        }
        
        .toast .toast-body {
            color: white;
            padding: 15px;
            font-weight: 500;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .section-card { margin-bottom: 16px; }
            .cart-total { font-size: 1.1rem; }
            .cart-total strong { font-size: 1.2rem; }
        }
    </style>
</head>
<body>

<!-- Navbar simple -->
<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">
            <i class="fas fa-utensils me-2"></i>NicaFood ERP
        </a>
        <a href="index.php" class="btn btn-outline-light btn-sm">
            <i class="fas fa-arrow-left me-1"></i>Volver al Menú
        </a>
    </div>
</nav>

<div class="container py-3">
    <h2 class="mb-4 fw-bold">
        <i class="fas fa-credit-card me-2 text-primary"></i>Finalizar Pedido
    </h2>
    
    <!-- Resumen del Pedido -->
    <div class="cart-summary mb-4">
        <h5 class="mb-3 fw-bold"><i class="fas fa-shopping-cart me-2"></i>Resumen del Pedido</h5>
        <div id="resumen-pedido">
            <p class="text-muted text-center py-3">Cargando productos...</p>
        </div>
        <div class="cart-total">
            <span>Total a Pagar:</span>
            <strong id="total-pagar">C$0.00</strong>
        </div>
    </div>
    
    <form id="formCheckout" novalidate>
        
        <!-- DATOS PERSONALES DEL CLIENTE -->
        <div class="card section-card">
            <div class="card-header section-header">
                <h5><i class="fas fa-id-card"></i>Datos Personales del Cliente</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <!-- Tipo de Persona -->
                    <div class="col-md-3">
                        <label class="form-label required">Tipo de Persona</label>
                        <select class="form-select" name="persona[tipo_persona]" required>
                            <option value="Nacional" selected>Nacional</option>
                            <option value="Extranjera">Extranjera</option>
                        </select>
                    </div>
                    
                    <!-- Tipo de Identificación -->
                    <div class="col-md-4">
                        <label class="form-label required">Tipo de Identificación</label>
                        <select class="form-select" name="persona[tipo_identificacion]" required>
                            <option value="Cedula" selected>Cédula</option>
                            <option value="Pasaporte">Pasaporte</option>
                            <option value="RUC">RUC</option>
                            <option value="DNI">DNI</option>
                            <option value="Residencia">Residencia</option>
                            <option value="Nit">NIT</option>
                        </select>
                    </div>
                    
                    <!-- Número de Identificación -->
                    <div class="col-md-5">
                        <label class="form-label required">Número de Identificación</label>
                        <input type="text" class="form-control" name="persona[numero_identificacion]" 
                               required placeholder="Ej: 001-010595-1000A" 
                               pattern="[0-9\-A-Za-z]+" title="Formato válido de identificación">
                    </div>
                    
                    <!-- Método de Pago Preferido -->
                    <div class="col-md-4">
                        <label class="form-label required">Método de Pago Preferido</label>
                        <select class="form-select" name="persona[metodo_pago_preferido]" required>
                            <option value="Efectivo" selected>Efectivo</option>
                            <option value="Tarjeta">Tarjeta Débito/Crédito</option>
                            <option value="Transferencia">Transferencia Bancaria</option>
                        </select>
                    </div>
                    
                    <!-- Nombres -->
                    <div class="col-md-4">
                        <label class="form-label required">Primer Nombre</label>
                        <input type="text" class="form-control" name="persona[primer_nombre]" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Segundo Nombre</label>
                        <input type="text" class="form-control" name="persona[segundo_nombre]">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label required">Apellidos</label>
                        <input type="text" class="form-control" name="persona[Apellidos]" required>
                    </div>
                    
                    <!-- Fecha de Nacimiento -->
                    <div class="col-md-3">
                        <label class="form-label">Fecha de Nacimiento</label>
                        <input type="date" class="form-control" name="persona[fecha_nacimiento]" max="<?= date('Y-m-d') ?>">
                    </div>
                    
                    <!-- Sexo -->
                    <div class="col-md-3">
                        <label class="form-label">Sexo</label>
                        <select class="form-select" name="persona[sexo]">
                            <option value="">Seleccionar</option>
                            <option value="Masculino">Masculino</option>
                            <option value="Femenino">Femenino</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>

<!-- ================= IDENTIDAD Y GÉNERO ================= -->
<div class="col-12">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">
                <i class="fas fa-venus-mars me-2 text-primary"></i>
                Identidad y Género 
                <small class="text-muted fw-normal">(Opcional y confidencial)</small>
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <!-- Sexo (registro) -->
                <div class="col-md-4">
                    <label class="form-label">Sexo (registro)</label>
                    <select class="form-select" name="persona[sexo]">
                        <option value="">Seleccionar</option>
                        <option value="Masculino">Masculino</option>
                        <option value="Femenino">Femenino</option>
                        <option value="Otro">Otro / No especificar</option>
                    </select>
                    <small class="text-muted">Para fines administrativos</small>
                </div>
                
                <!-- Identidad de Género -->
                <div class="col-md-4">
                    <label class="form-label">Identidad de Género</label>
                    <select class="form-select" name="persona[genero]">
                        <option value="">Seleccionar</option>
                        <option value="Hombre">Hombre</option>
                        <option value="Mujer">Mujer</option>
                        <option value="No_Binario">No binario</option>
                        <option value="Genero_Fluido">Género fluido</option>
                        <option value="Otro">Otra identidad</option>
                        <option value="Prefiero_no_decir">Prefiero no decir</option>
                    </select>
                    <small class="text-muted">Cómo te identificas</small>
                </div>
                
                <!-- Orientación Sexual -->
                <div class="col-md-4">
                    <label class="form-label">Orientación Sexual</label>
                    <select class="form-select" name="persona[orientacion_sexual]">
                        <option value="">Seleccionar</option>
                        <option value="Heterosexual">Heterosexual</option>
                        <option value="Homosexual">Homosexual</option>
                        <option value="Bisexual">Bisexual</option>
                        <option value="Pansexual">Pansexual</option>
                        <option value="Asexual">Asexual</option>
                        <option value="Otro">Otra orientación</option>
                        <option value="Prefiero_no_decir">Prefiero no decir</option>
                    </select>
                    <small class="text-muted">Información confidencial</small>
                </div>
                
                <!-- Pronombres Preferidos -->
                <div class="col-md-4">
                    <label class="form-label">Pronombres Preferidos</label>
                    <select class="form-select" name="persona[pronombres_preferidos]">
                        <option value="">No especificar</option>
                        <option value="El">Él</option>
                        <option value="Ella">Ella</option>
                        <option value="Elle">Elle</option>
                        <option value="Ellx">Ellx</option>
                        <option value="Prefiero_no_decir">Prefiero no decir</option>
                    </select>
                    <small class="text-muted">Para un trato respetuoso</small>
                </div>
                
                <!-- Consentimiento -->
                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="consentimiento" name="persona[consentimiento_datos_sensibles]" value="1">
                        <label class="form-check-label" for="consentimiento">
                            <strong>Consentimiento de datos sensibles</strong>
                        </label>
                    </div>
                    <small class="text-muted">
                        Autorizo el registro de esta información bajo estricta confidencialidad, conforme a la Ley de Protección de Datos Personales. 
                        Estos datos no serán compartidos con terceros sin mi consentimiento explícito.
                    </small>
                    <input type="hidden" name="persona[fecha_consentimiento]" value="<?= date('Y-m-d H:i:s') ?>">
                </div>
            </div>
        </div>
    </div>
</div>
                    <!-- Estado Civil -->
                    <div class="col-md-3">
                        <label class="form-label">Estado Civil</label>
                        <select class="form-select" name="persona[estado_civil]">
                            <option value="Soltero" selected>Soltero/a</option>
                            <option value="Casado">Casado/a</option>
                            <option value="Union_Libre">Unión Libre</option>
                            <option value="Divorciado">Divorciado/a</option>
                            <option value="Viudo">Viudo/a</option>
                        </select>
                    </div>
                    
                    <!-- Tipo de Sangre -->
                    <div class="col-md-3">
                        <label class="form-label">Tipo de Sangre</label>
                        <select class="form-select" name="persona[Tipo_sangre]">
                            <option value="">Seleccionar</option>
                            <optgroup label="Positivo">
                                <option value="A+">A+</option>
                                <option value="B+">B+</option>
                                <option value="AB+">AB+</option>
                                <option value="O+">O+</option>
                            </optgroup>
                            <optgroup label="Negativo">
                                <option value="A-">A-</option>
                                <option value="B-">B-</option>
                                <option value="AB-">AB-</option>
                                <option value="O-">O-</option>
                            </optgroup>
                        </select>
                    </div>
                    
                    <!-- Contactos -->
                    <div class="col-md-4">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="persona[email]" 
                               placeholder="correo@ejemplo.com">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label required">Teléfono Principal</label>
                        <input type="tel" class="form-control" name="persona[telefono_principal]" 
                               required placeholder="+505 8888-9999" 
                               pattern="\+?[0-9\s\-]+" title="Formato: +505 8888-9999">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Teléfono de Emergencia</label>
                        <input type="tel" class="form-control" name="persona[telefono_emergencia]" 
                               placeholder="+505 8888-9999">
                    </div>
                    
                    <!-- Dirección -->
                    <div class="col-12">
                        <label class="form-label required">Dirección Completa</label>
                        <textarea class="form-control" name="persona[direccion]" rows="2" 
                                  required placeholder="Dirección completa de entrega (incluya referencias)"></textarea>
                    </div>
                    
                    <!-- Ubicación -->
                    <div class="col-md-4">
                        <label class="form-label">Ciudad</label>
                        <input type="text" class="form-control" name="persona[ciudad]" value="Managua">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Departamento/Estado</label>
                        <input type="text" class="form-control" name="persona[departamento_estado]" value="Managua">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">País</label>
                        <select class="form-select" name="persona[pais]">
                            <option value="NI" selected>Nicaragua</option>
                            <option value="CR">Costa Rica</option>
                            <option value="SV">El Salvador</option>
                            <option value="GT">Guatemala</option>
                            <option value="HN">Honduras</option>
                            <option value="PA">Panamá</option>
                        </select>
                    </div>
                    
                    <!-- Enfermedades / Alergias -->
                    <div class="col-12">
                        <label class="form-label">Enfermedades / Alergias / Condiciones Médicas</label>
                        <textarea class="form-control" name="persona[Enfermedades]" rows="2" 
                                  placeholder="Ej: Alergia a mariscos, Diabetes, Hipertensión, Celiaco..."></textarea>
                        <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Esta información es confidencial y solo se usará para tu seguridad</small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- EMPLEADO QUE DESPACHA -->
        <div class="card section-card">
            <div class="card-header section-header" style="background: linear-gradient(135deg, #059669 0%, #047857 100%);">
                <h5><i class="fas fa-user-tie"></i>Empleado que Despacha</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label required">Empleado Responsable</label>
                        <select class="form-select" name="id_empleado_despacha" required>
                            <option value="">Seleccionar empleado...</option>
                            <?php foreach ($empleados as $emp): ?>
                            <option value="<?= $emp['id_empleado'] ?>">
                                <?= htmlspecialchars($emp['primer_nombre'] . ' ' . $emp['Apellidos'] . ' - ' . $emp['cargo']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted">Empleado que registra y despacha este pedido</small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- ENVÍO A DOMICILIO -->
        <div class="card section-card">
            <div class="card-header section-header" style="background: linear-gradient(135deg, var(--warning) 0%, #d97706 100%);">
                <h5><i class="fas fa-shipping-fast"></i>Información de Envío</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="tieneEnvio" name="tiene_envio" value="1">
                        <label class="form-check-label fw-bold" for="tieneEnvio">
                            ¿Requiere envío a domicilio?
                        </label>
                    </div>
                    <small class="text-muted">Marque esta casilla si el pedido será entregado a domicilio</small>
                </div>
                
                <!-- Sección de envío (se muestra solo si tieneEnvio está marcado) -->
                <div id="seccionEnvio">
                    <h6 class="mb-3 fw-bold"><i class="fas fa-map-marker-alt me-2 text-warning"></i>Detalles de Entrega</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Repartidor Asignado</label>
                            <select class="form-select" name="envio[id_repartidor]">
                                <option value="">Asignar después...</option>
                                <!-- Aquí se cargarían los repartidores disponibles vía AJAX -->
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Zona de Entrega</label>
                            <select class="form-select" name="envio[id_tarifa_zona]">
                                <option value="">Seleccionar zona...</option>
                                <?php foreach ($zonas_envio as $zona): ?>
                                <option value="<?= $zona['id_tarifa'] ?>" data-costo="<?= $zona['costo_envio'] ?>">
                                    <?= htmlspecialchars($zona['nombre_zona']) ?> - C$<?= number_format($zona['costo_envio'], 2) ?> (<?= $zona['tiempo_estimado_min'] ?> min)
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label required">Dirección Exacta de Entrega</label>
                            <textarea class="form-control" name="envio[direccion_entrega]" rows="2" 
                                      placeholder="Dirección completa con referencias (ej: De la iglesia 2 cuadras al norte, casa azul)"></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Costo de Envío</label>
                            <input type="number" step="0.01" class="form-control" name="envio[costo_envio]" value="30.00" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Latitud (GPS)</label>
                            <input type="text" class="form-control" name="envio[latitud]" placeholder="12.136">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Longitud (GPS)</label>
                            <input type="text" class="form-control" name="envio[longitud]" placeholder="-86.273">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Botones de Acción -->
        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
            <a href="index.php" class="btn btn-secondary">
                <i class="fas fa-times me-2"></i>Cancelar Pedido
            </a>
            <button type="submit" class="btn btn-success btn-lg" id="btnSubmit">
                <i class="fas fa-check-circle me-2"></i>Confirmar y Procesar Pedido
            </button>
        </div>
        
    </form>
</div>

<!-- Toast Container para notificaciones -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100">
    <div id="liveToast" class="toast hide" role="alert">
        <div class="toast-header">
            <strong class="me-auto">NicaFood ERP</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body" id="toastMessage"></div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// ==================== ESTADO DEL CARRITO ====================
let carrito = JSON.parse(localStorage.getItem('nicafood_cart')) || [];

// ==================== FUNCIONES PRINCIPALES ====================

// Mostrar resumen del carrito
function mostrarResumen() {
    const container = document.getElementById('resumen-pedido');
    const totalEl = document.getElementById('total-pagar');
    
    if (!carrito || carrito.length === 0) {
        container.innerHTML = `
            <div class="text-center py-4 text-muted">
                <i class="fas fa-shopping-basket fa-2x mb-2 opacity-50"></i>
                <p class="mb-0">Tu carrito está vacío</p>
                <small><a href="index.php" class="text-primary">Agregar productos</a></small>
            </div>
        `;
        totalEl.textContent = 'C$0.00';
        document.getElementById('btnSubmit').disabled = true;
        return;
    }
    
    let html = '';
    let subtotal = 0;
    
    carrito.forEach(item => {
        const itemTotal = item.precio * item.cantidad;
        subtotal += itemTotal;
        
        html += `
            <div class="cart-item">
                <div>
                    <div class="cart-item-name">${item.nombre}</div>
                    <div class="cart-item-meta">${item.cantidad} x C$${item.precio.toFixed(2)}</div>
                </div>
                <div class="cart-item-price">C$${itemTotal.toFixed(2)}</div>
            </div>
        `;
    });
    
    const impuesto = subtotal * 0.15;
    const total = subtotal + impuesto;
    
    html += `
        <div class="cart-item" style="border-top: 2px solid #e2e8f0; margin-top: 8px; padding-top: 12px;">
            <div>
                <div class="cart-item-name">Subtotal</div>
                <div class="cart-item-meta">IVA 15% incluido</div>
            </div>
            <div class="cart-item-price">C$${subtotal.toFixed(2)}</div>
        </div>
        <div class="cart-item">
            <div class="cart-item-name">IVA (15%)</div>
            <div class="cart-item-price">C$${impuesto.toFixed(2)}</div>
        </div>
    `;
    
    container.innerHTML = html;
    totalEl.textContent = 'C$' + total.toFixed(2);
    document.getElementById('btnSubmit').disabled = false;
}

// Toggle sección de envío
document.getElementById('tieneEnvio')?.addEventListener('change', function() {
    const seccion = document.getElementById('seccionEnvio');
    const costoInput = document.querySelector('input[name="envio[costo_envio]"]');
    
    if (this.checked) {
        seccion.classList.add('active');
        // Actualizar costo si hay zona seleccionada
        const zonaSelect = document.querySelector('select[name="envio[id_tarifa_zona]"]');
        if (zonaSelect?.value) {
            const option = zonaSelect.options[zonaSelect.selectedIndex];
            if (option.dataset.costo) {
                costoInput.value = option.dataset.costo;
            }
        }
    } else {
        seccion.classList.remove('active');
        costoInput.value = '0.00';
    }
});

// Actualizar costo de envío al cambiar zona
document.querySelector('select[name="envio[id_tarifa_zona]"]')?.addEventListener('change', function() {
    const option = this.options[this.selectedIndex];
    const costoInput = document.querySelector('input[name="envio[costo_envio]"]');
    
    if (option?.dataset.costo) {
        costoInput.value = option.dataset.costo;
    }
});

// Validar formulario antes de enviar
function validarFormulario() {
    const form = document.getElementById('formCheckout');
    
    // Validación nativa de HTML5
    if (!form.checkValidity()) {
        form.reportValidity();
        showToast('⚠️ Por favor complete todos los campos requeridos', 'danger');
        return false;
    }
    
    // Validación personalizada: carrito no vacío
    if (!carrito || carrito.length === 0) {
        showToast('⚠️ Tu carrito está vacío. Agrega productos antes de continuar', 'danger');
        return false;
    }
    
    // Validación: si tiene envío, verificar dirección de entrega
    const tieneEnvio = document.getElementById('tieneEnvio').checked;
    if (tieneEnvio) {
        const direccionEntrega = document.querySelector('textarea[name="envio[direccion_entrega]"]')?.value?.trim();
        if (!direccionEntrega) {
            showToast('⚠️ Debe ingresar la dirección exacta de entrega', 'danger');
            document.querySelector('textarea[name="envio[direccion_entrega]"]')?.focus();
            return false;
        }
    }
    
    return true;
}

// Enviar formulario
document.getElementById('formCheckout')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    if (!validarFormulario()) return;
    
    const btn = document.getElementById('btnSubmit');
    const originalBtnText = btn.innerHTML;
    
    // Deshabilitar botón para prevenir doble envío
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Procesando...';
    
    try {
        // Serializar datos del formulario
        const formData = new FormData(this);
        const data = {
            persona: {},
            items: carrito,
            id_empleado_despacha: formData.get('id_empleado_despacha'),
            tiene_envio: document.getElementById('tieneEnvio').checked
        };
        
        // Procesar campos anidados de persona
        for (let [key, value] of formData.entries()) {
            if (key.startsWith('persona[')) {
                const match = key.match(/persona\[(\w+)\]/);
                if (match) data.persona[match[1]] = value;
            }
            if (key.startsWith('envio[') && data.tiene_envio) {
                if (!data.envio) data.envio = {};
                const match = key.match(/envio\[(\w+)\]/);
                if (match) data.envio[match[1]] = value;
            }
        }
        
        console.log('📦 Enviando pedido:', data);
        
        const response = await fetch('api/procesar_pedido_completo.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        
        // Verificar que la respuesta sea JSON válido
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            const text = await response.text();
            throw new Error('Respuesta inválida del servidor: ' + text.substring(0, 200));
        }
        
        const result = await response.json();
        console.log('📩 Respuesta:', result);
        
        if (result.success) {
            showToast('✅ ¡Pedido registrado exitosamente!', 'success');
            
            // Mostrar detalles del pedido
            setTimeout(() => {
                alert(`✅ Pedido Confirmado\n\n📄 Factura: ${result.data.numero_factura}\n👤 Cliente: ${result.data.codigo_cliente}\n💰 Total: C$${result.data.total.toFixed(2)}\n\nGracias por tu compra en NicaFood!`);
                
                // Limpiar carrito y redirigir
                localStorage.removeItem('nicafood_cart');
                window.location.href = 'index.php';
            }, 1500);
        } else {
            showToast('❌ Error: ' + (result.message || 'Error desconocido'), 'danger');
            btn.disabled = false;
            btn.innerHTML = originalBtnText;
        }
    } catch (error) {
        console.error('❌ Error:', error);
        showToast('❌ Error de conexión: ' + error.message, 'danger');
        btn.disabled = false;
        btn.innerHTML = originalBtnText;
    }
});

// Mostrar notificación toast
function showToast(message, type = 'success') {
    const toast = document.getElementById('liveToast');
    const toastMessage = document.getElementById('toastMessage');
    
    toast.className = `toast hide bg-${type} text-white`;
    toastMessage.textContent = message;
    
    const bsToast = new bootstrap.Toast(toast, { delay: 4000 });
    bsToast.show();
}

// ==================== INICIALIZACIÓN ====================
document.addEventListener('DOMContentLoaded', function() {
    mostrarResumen();
    
    // Validación en tiempo real para teléfono
    document.querySelectorAll('input[type="tel"]').forEach(input => {
        input.addEventListener('input', function(e) {
            // Permitir solo números, +, espacios y guiones
            this.value = this.value.replace(/[^\d+\-\s]/g, '');
        });
    });
    
    // Validación en tiempo real para email
    document.querySelector('input[name="persona[email]"]')?.addEventListener('blur', function() {
        if (this.value && !this.validity.valid) {
            this.setCustomValidity('Por favor ingresa un email válido');
        } else {
            this.setCustomValidity('');
        }
    });
});
</script>

</body>
</html>