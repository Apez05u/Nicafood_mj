<?php
/**
 * Conexión a base de datos y funciones reutilizables
 * NicaFood ERP v1.0
 */

// Configuración
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'nicafood_erp');
define('DB_CHARSET', 'utf8mb4');



/**
 * Obtener conexión PDO
 */
function getDB() {
    static $conn = null;
    
    if ($conn === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $conn = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            if (getenv('APP_ENV') === 'production') {
                error_log($e->getMessage());
                die("Error de conexión al sistema");
            }
            die("Error de conexión: " . $e->getMessage());
        }
    }
    
    return $conn;
}

/**
 * Obtener conexión MySQLi (para compatibilidad)
 */
function getMySQLi() {
    static $conn = null;
    
    if ($conn === null) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($conn->connect_error) {
            die("Error de conexión MySQLi: " . $conn->connect_error);
        }
        $conn->set_charset(DB_CHARSET);
    }
    
    return $conn;
}

/**
 * Funciones para Persona
 */
function obtenerPersonaPorId($id) {
    $stmt = getDB()->prepare("SELECT * FROM Persona WHERE id_persona = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// Ejemplo: función crearPersona() actualizada
function crearPersona($datos) {
    $db = getDB();
    
    $stmt = $db->prepare("INSERT INTO persona (
        tipo_persona, tipo_identificacion, numero_identificacion,
        primer_nombre, segundo_nombre, Apellidos, fecha_nacimiento,
        sexo, genero, orientacion_sexual, pronombres_preferidos,
        consentimiento_datos_sensibles, fecha_consentimiento,
        estado_civil, email, telefono_principal, telefono_emergencia,
        direccion, ciudad, departamento_estado, pais, Enfermedades, Tipo_sangre,
        fecha_registro, fecha_modificacion
    ) VALUES (
        :tipo_persona, :tipo_identificacion, :numero_identificacion,
        :primer_nombre, :segundo_nombre, :Apellidos, :fecha_nacimiento,
        :sexo, :genero, :orientacion_sexual, :pronombres_preferidos,
        :consentimiento_datos_sensibles, :fecha_consentimiento,
        :estado_civil, :email, :telefono_principal, :telefono_emergencia,
        :direccion, :ciudad, :departamento_estado, :pais, :Enfermedades, :Tipo_sangre,
        NOW(), NOW()
    )");
    
    $stmt->execute([
        // ... parámetros existentes ...
        'sexo' => $datos['sexo'] ?? null,
        'genero' => $datos['genero'] ?? null,
        'orientacion_sexual' => $datos['orientacion_sexual'] ?? null,
        'pronombres_preferidos' => $datos['pronombres_preferidos'] ?? null,
        'consentimiento_datos_sensibles' => !empty($datos['consentimiento_datos_sensibles']) ? 1 : 0,
        'fecha_consentimiento' => $datos['fecha_consentimiento'] ?? null,
        // ... resto ...
    ]);
    
    return getDB()->lastInsertId();
}

/**
 * Funciones para Empleado
 */
function obtenerEmpleadosConPersona($limit = 50, $offset = 0) {
    $stmt = getDB()->prepare("
        SELECT e.*, p.primer_nombre, p.segundo_nombre, p.Apellidos, p.email, p.telefono_principal,
               c.nombre as cargo_nombre, a.nombre as area_nombre, d.nombre as depto_nombre
        FROM Empleado e
        INNER JOIN Persona p ON e.id_persona = p.id_persona
        LEFT JOIN Cargo c ON e.id_cargo = c.id_cargo
        LEFT JOIN Area a ON e.id_area = a.id_area
        LEFT JOIN Departamento d ON a.id_depto = d.id_depto
        WHERE e.estado = 'Activo'
        ORDER BY e.fecha_ingreso DESC
        LIMIT :limit OFFSET :offset
    ");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

function crearEmpleado($id_persona, $datos) {
    $stmt = getDB()->prepare("INSERT INTO Empleado (
        id_persona, codigo_emp, id_unidad, id_area, id_cargo,
        fecha_ingreso, tipo_categoria, aplica_vacaciones, Tipo_sangre,
        fecha_registro, fecha_modificacion
    ) VALUES (
        :id_persona, :codigo_emp, :id_unidad, :id_area, :id_cargo,
        :fecha_ingreso, :tipo_categoria, :aplica_vacaciones, :Tipo_sangre,
        NOW(), NOW()
    )");
    
    $stmt->execute([
        'id_persona' => $id_persona,
        'codigo_emp' => $datos['codigo_emp'] ?? 'EMP-' . date('Ymd') . '-' . rand(1000,9999),
        'id_unidad' => $datos['id_unidad'] ?? 1,
        'id_area' => $datos['id_area'],
        'id_cargo' => $datos['id_cargo'],
        'fecha_ingreso' => $datos['fecha_ingreso'] ?? date('Y-m-d'),
        'tipo_categoria' => $datos['tipo_categoria'] ?? 'Operativo',
        'aplica_vacaciones' => $datos['aplica_vacaciones'] ?? 1,
        'Tipo_sangre' => $datos['Tipo_sangre'] ?? null,
    ]);
    
    return getDB()->lastInsertId();
}

/**
 * Funciones para Usuario y Login
 */
/**
 * Funciones para Usuario y Login
 */
function verificarLogin($username, $password) {
    $db = getDB();
    
    // Consulta corregida - tabla Usuario correctamente referenciada
    $sql = "SELECT u.*, e.codigo_emp, p.primer_nombre, p.Apellidos, r.nombre as rol_nombre
            FROM `Usuario` u
            INNER JOIN `Empleado` e ON u.id_empleado = e.id_empleado
            INNER JOIN `Persona` p ON e.id_persona = p.id_persona
            INNER JOIN `Rol` r ON u.id_rol = r.id_rol
            WHERE u.username = :username AND u.estado = 1";
    
    $stmt = $db->prepare($sql);
    $stmt->execute(['username' => $username]);
    $usuario = $stmt->fetch();
    
    if ($usuario && $password === $usuario['contraseña']) {
        // Actualizar último acceso
        $update = $db->prepare("UPDATE `Usuario` SET ultimo_acceso = NOW() WHERE id_usuario = :id");
        $update->execute(['id' => $usuario['id_usuario']]);
        
        return $usuario;
    }
    
    return false;
}

/**
 * Funciones para Productos y Menú
 */
// En obtenerProductosMenu(), asegúrate que las columnas coincidan con tu tabla `producto`:
function obtenerProductosMenu($id_categoria = null) {
    $sql = "SELECT id_producto, codigo_producto, nombre, precio_venta, precio_menu, visible_pos, activo 
            FROM producto WHERE activo = 1 AND visible_pos = 1"; // ← tabla en minúscula
    if ($id_categoria) {
        $sql .= " AND id_categoria = :id_categoria";
    }
    $sql .= " ORDER BY nombre ASC";
    
    $stmt = getDB()->prepare($sql);
    if ($id_categoria) {
        $stmt->execute(['id_categoria' => $id_categoria]);
    } else {
        $stmt->execute();
    }
    return $stmt->fetchAll();
}

function obtenerCombosActivos() {
    $stmt = getDB()->prepare("SELECT id_combo, nombre, precio_combo FROM Combo WHERE activo = 1");
    $stmt->execute();
    return $stmt->fetchAll();
}

function obtenerCategoriasProductos() {
    $stmt = getDB()->prepare("SELECT id_categoria, nombre FROM Categoria WHERE tipo = 'Producto'");
    $stmt->execute();
    return $stmt->fetchAll();
}
?>