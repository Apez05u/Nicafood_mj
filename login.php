<?php
/**
 * NicaFood ERP - Login Mejorado
 * Archivo: login.php
 * 
 * Características:
 * - Diseño moderno con animaciones y gradientes
 * - Toggle para mostrar/ocultar contraseña
 * - Validaciones en tiempo real
 * - Mensajes de error elegantes
 * - Responsive y accesible
 * - Compatible con tu sistema actual
 */

require_once 'config/database.php';
session_start();

// Si ya está logueado, redirigir al dashboard
if (isset($_SESSION['id_usuario'])) {
    header("Location: dashboard.php");
    exit;
}

$error = '';
$success = '';

// Procesar login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);
    
    if (empty($username) || empty($password)) {
        $error = "⚠️ Por favor completa todos los campos";
    } else {
        $usuario = verificarLogin($username, $password);
        
        if ($usuario) {
            // Crear sesión
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['username'] = $usuario['username'];
            $_SESSION['nombre'] = $usuario['primer_nombre'] . ' ' . $usuario['Apellidos'];
            $_SESSION['rol'] = $usuario['rol_nombre'];
            $_SESSION['id_empleado'] = $usuario['id_empleado'];
            
            // Actualizar último acceso
            $db = getDB();
            $update = $db->prepare("UPDATE usuario SET ultimo_acceso = NOW() WHERE id_usuario = ?");
            $update->execute([$usuario['id_usuario']]);
            
            // Cookie de recordarme (opcional)
            if ($remember) {
                setcookie('nicafood_user', $username, time() + (86400 * 30), "/"); // 30 días
            }
            
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "❌ Usuario o contraseña incorrectos";
        }
    }
}

// Prefill username si hay cookie
$prefill_user = $_COOKIE['nicafood_user'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - NicaFood </title>
    
    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #0b3185;
            --primary-dark: #082566;
            --primary-light: #1e4db3;
            --accent: #ff9e00;
            --success: #10b981;
            --danger: #ef4444;
            --light: #f8fafc;
            --dark: #1e293b;
            --gradient: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', 'Segoe UI', system-ui, sans-serif;
            background: var(--gradient);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }
        
        /* Background decoration */
        body::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: 
                radial-gradient(circle at 20% 80%, rgba(255,158,0,0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255,255,255,0.05) 0%, transparent 50%);
            animation: float 20s ease-in-out infinite;
            pointer-events: none;
        }
        
        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            25% { transform: translate(2%, 2%) rotate(0.5deg); }
            50% { transform: translate(0, 4%) rotate(0deg); }
            75% { transform: translate(-2%, 2%) rotate(-0.5deg); }
        }
        
        /* Login Card */
        .login-card {
            background: white;
            border-radius: 24px;
            box-shadow: 
                0 25px 50px -12px rgba(0, 0, 0, 0.25),
                0 0 0 1px rgba(255, 255, 255, 0.1);
            width: 100%;
            max-width: 420px;
            padding: 40px 35px;
            position: relative;
            z-index: 10;
            animation: slideUp 0.6s ease-out;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Logo/Header */
        .login-header {
            text-align: center;
            margin-bottom: 35px;
        }
        
        .login-logo {
            width: 80px;
            height: 80px;
            background: var(--gradient);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 10px 30px rgba(11, 49, 133, 0.3);
        }
        
        .login-logo i {
            font-size: 2rem;
            color: white;
        }
        
        .login-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 5px;
        }
        
        .login-header p {
            color: #64748b;
            font-size: 0.9rem;
            margin: 0;
        }
        
        /* Form Styles */
        .form-label {
            font-weight: 500;
            color: var(--dark);
            font-size: 0.9rem;
            margin-bottom: 8px;
        }
        
        .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px 16px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: #f8fafc;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(11, 49, 133, 0.1);
            background: white;
            outline: none;
        }
        
        .form-control.is-invalid {
            border-color: var(--danger);
        }
        
        .form-control.is-invalid:focus {
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
        }
        
        /* Password Toggle */
        .password-wrapper {
            position: relative;
        }
        
        .password-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #64748b;
            cursor: pointer;
            padding: 5px;
            transition: color 0.2s;
            z-index: 5;
        }
        
        .password-toggle:hover {
            color: var(--primary);
        }
        
        /* Remember & Forgot */
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 20px 0 25px;
            font-size: 0.9rem;
        }
        
        .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .form-check-input {
            width: 18px;
            height: 18px;
            border: 2px solid #cbd5e1;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .form-check-label {
            color: #64748b;
            cursor: pointer;
            font-weight: 400;
        }
        
        .forgot-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }
        
        .forgot-link:hover {
            color: var(--primary-dark);
            text-decoration: none;
        }
        
        /* Login Button */
        .btn-login {
            background: var(--gradient);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            width: 100%;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            position: relative;
            overflow: hidden;
        }
        
        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-login:hover::before {
            left: 100%;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(11, 49, 133, 0.4);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .btn-login:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }
        
        /* Alert Messages */
        .alert-custom {
            border: none;
            border-radius: 12px;
            padding: 14px 16px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideIn 0.3s ease;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .alert-error {
            background: #fef2f2;
            color: var(--danger);
            border-left: 4px solid var(--danger);
        }
        
        .alert-success {
            background: #f0fdf4;
            color: var(--success);
            border-left: 4px solid var(--success);
        }
        
        .alert-custom i {
            font-size: 1.1rem;
            flex-shrink: 0;
        }
        
        /* Demo Credentials */
        .demo-credentials {
            background: #f1f5f9;
            border-radius: 10px;
            padding: 15px;
            margin-top: 25px;
            text-align: center;
        }
        
        .demo-credentials small {
            color: #64748b;
            display: block;
            margin-bottom: 8px;
        }
        
        .demo-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--dark);
            border: 1px solid #e2e8f0;
        }
        
        .demo-badge i {
            color: var(--accent);
        }
        
        /* Footer */
        .login-footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }
        
        .login-footer small {
            color: #94a3b8;
            font-size: 0.8rem;
        }
        
        /* Responsive */
        @media (max-width: 480px) {
            .login-card {
                padding: 30px 25px;
                margin: 10px;
            }
            
            .login-logo {
                width: 70px;
                height: 70px;
            }
            
            .login-header h1 {
                font-size: 1.3rem;
            }
        }
        
        /* Loading Spinner */
        .spinner {
            display: inline-block;
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 0.8s linear infinite;
            vertical-align: middle;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Input Icons */
        .input-icon {
            position: relative;
        }
        
        .input-icon i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1rem;
            z-index: 3;
        }
        
        .input-icon .form-control {
            padding-left: 45px;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <!-- Header -->
        <div class="login-header">
            <div class="login-logo">
                <i class="fas fa-utensils"></i>
            </div>
            <h1>NicaFood </h1>
            <p>Ingresa para continuar</p>
        </div>
        
        <!-- Messages -->
        <?php if ($error): ?>
        <div class="alert-custom alert-error mb-4" role="alert">
            <i class="fas fa-exclamation-circle"></i>
            <span><?= htmlspecialchars($error) ?></span>
        </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
        <div class="alert-custom alert-success mb-4" role="alert">
            <i class="fas fa-check-circle"></i>
            <span><?= htmlspecialchars($success) ?></span>
        </div>
        <?php endif; ?>
        
        <!-- Form -->
        <form method="POST" id="loginForm" novalidate>
            <!-- Username -->
            <div class="mb-4">
                <label class="form-label" for="username">
                    <i class="fas fa-user me-2"></i>Usuario
                </label>
                <div class="input-icon">
                    <i class="fas fa-user"></i>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="username" 
                        name="username" 
                        placeholder="Ingresa tu usuario"
                        value="<?= htmlspecialchars($prefill_user) ?>"
                        required
                        autocomplete="username"
                    >
                </div>
                <div class="invalid-feedback">Por favor ingresa tu usuario</div>
            </div>
            
            <!-- Password -->
            <div class="mb-3">
                <label class="form-label" for="password">
                    <i class="fas fa-lock me-2"></i>Contraseña
                </label>
                <div class="input-icon password-wrapper">
                    <i class="fas fa-lock"></i>
                    <input 
                        type="password" 
                        class="form-control" 
                        id="password" 
                        name="password" 
                        placeholder="Ingresa tu contraseña"
                        required
                        autocomplete="current-password"
                        minlength="6"
                    >
                    <button type="button" class="password-toggle" id="togglePassword" aria-label="Mostrar contraseña">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <div class="invalid-feedback">La contraseña debe tener al menos 6 caracteres</div>
            </div>
            
            <!-- Options -->
            <div class="form-options">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember" name="remember" value="1">
                    <label class="form-check-label" for="remember">Recordarme</label>
                </div>
                <a href="#" class="forgot-link" onclick="showRecovery(); return false;">
                    ¿Olvidaste tu contraseña?
                </a>
            </div>
            
            <!-- Submit -->
            <button type="submit" class="btn-login" id="btnSubmit">
                <span id="btnText">
                    <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                </span>
                <span id="btnLoading" style="display: none;">
                    <span class="spinner"></span> Procesando...
                </span>
            </button>
        </form>
        
        <!-- Demo Credentials -->
        <div class="demo-credentials">
            <small>💡 Credenciales de demostración:</small>
            <div class="demo-badge">
                <i class="fas fa-user"></i> Carlos
            </div>
            <div class="demo-badge mt-2">
                <i class="fas fa-key"></i> Caja2026*
            </div>
        </div>
        
        <!-- Footer -->
        <div class="login-footer">
            <small>
                <i class="fas fa-shield-alt me-1"></i>
                Sistema seguro • NicaFood ERP v1.0
            </small>
        </div>
    </div>

    <!-- Recovery Modal (Simple) -->
    <div class="modal fade" id="recoveryModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title">Recuperar Contraseña</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small">Ingresa tu usuario o email y te enviaremos instrucciones para restablecer tu contraseña.</p>
                    <input type="text" class="form-control mb-3" placeholder="Usuario o email">
                    <button class="btn btn-primary w-100" onclick="alert('📧 En producción, aquí se enviaría un email de recuperación')">
                        <i class="fas fa-paper-plane me-2"></i>Enviar Instrucciones
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        
        togglePassword?.addEventListener('click', function() {
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;
            this.querySelector('i').className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
            this.setAttribute('aria-label', type === 'password' ? 'Mostrar contraseña' : 'Ocultar contraseña');
        });
        
        // Form validation
        const form = document.getElementById('loginForm');
        const btnSubmit = document.getElementById('btnSubmit');
        const btnText = document.getElementById('btnText');
        const btnLoading = document.getElementById('btnLoading');
        
        form?.addEventListener('submit', function(e) {
            // Validación básica antes de enviar
            const username = document.getElementById('username').value.trim();
            const password = passwordInput.value;
            
            if (!username || !password) {
                e.preventDefault();
                showToast('Por favor completa todos los campos', 'error');
                return;
            }
            
            if (password.length < 6) {
                e.preventDefault();
                showToast('La contraseña debe tener al menos 6 caracteres', 'error');
                return;
            }
            
            // Mostrar loading
            btnSubmit.disabled = true;
            btnText.style.display = 'none';
            btnLoading.style.display = 'inline-flex';
        });
        
        // Simple toast notification
        function showToast(message, type = 'info') {
            // Crear toast dinámico si no existe
            let toastContainer = document.getElementById('toastContainer');
            if (!toastContainer) {
                toastContainer = document.createElement('div');
                toastContainer.id = 'toastContainer';
                toastContainer.className = 'position-fixed bottom-0 end-0 p-3';
                toastContainer.style.zIndex = '9999';
                document.body.appendChild(toastContainer);
            }
            
            const toast = document.createElement('div');
            toast.className = `toast align-items-center text-white bg-${type === 'error' ? 'danger' : 'success'} border-0 mb-2`;
            toast.setAttribute('role', 'alert');
            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            `;
            
            toastContainer.appendChild(toast);
            const bsToast = new bootstrap.Toast(toast, { delay: 4000 });
            bsToast.show();
            
            // Auto remove
            toast.addEventListener('hidden.bs.toast', () => toast.remove());
        }
        
        // Recovery modal
        function showRecovery() {
            const modal = new bootstrap.Modal(document.getElementById('recoveryModal'));
            modal.show();
        }
        
        // Auto-focus username on load
        document.addEventListener('DOMContentLoaded', function() {
            const usernameInput = document.getElementById('username');
            if (usernameInput && !usernameInput.value) {
                usernameInput.focus();
            }
            
            // Enter key submit
            document.querySelectorAll('.form-control').forEach(input => {
                input.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        form?.requestSubmit();
                    }
                });
            });
        });
        
        // Prevent form resubmission on back button
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>
</html>