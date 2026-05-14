<?php
session_start();
require_once '../config/database.php';

// Asegurar que sea JSON
header('Content-Type: application/json');

try {
    if (!isset($_SESSION['id_usuario'])) {
        throw new Exception("No autorizado. Inicia sesión nuevamente.");
    }

    $db = getDB();
    $db->beginTransaction();

    $accion = $_POST['accion'] ?? '';
    $persona = $_POST['persona'] ?? [];
    $empleado = $_POST['empleado'] ?? [];

    if ($accion === 'crear') {
        // Validar campos obligatorios
        if (empty($persona['numero_identificacion'])) {
            throw new Exception("El número de identificación es requerido");
        }
        if (empty($persona['primer_nombre'])) {
            throw new Exception("El primer nombre es requerido");
        }
        if (empty($persona['Apellidos'])) {
            throw new Exception("Los apellidos son requeridos");
        }
        if (empty($persona['telefono_principal'])) {
            throw new Exception("El teléfono principal es requerido");
        }
        if (empty($persona['direccion'])) {
            throw new Exception("La dirección es requerida");
        }
        if (empty($empleado['id_cargo'])) {
            throw new Exception("Debe seleccionar un cargo");
        }

        // ================= 1. CREAR PERSONA =================
        $stmt = $db->prepare("INSERT INTO persona (
            tipo_persona, tipo_identificacion, numero_identificacion,
            primer_nombre, segundo_nombre, Apellidos, fecha_nacimiento,
            sexo, estado_civil, email, telefono_principal, telefono_emergencia,
            direccion, ciudad, departamento_estado, pais, Enfermedades, Tipo_sangre,
            fecha_registro, fecha_modificacion
        ) VALUES (
            :tipo_persona, :tipo_identificacion, :numero_identificacion,
            :primer_nombre, :segundo_nombre, :Apellidos, :fecha_nacimiento,
            :sexo, :estado_civil, :email, :telefono_principal, :telefono_emergencia,
            :direccion, :ciudad, :departamento_estado, :pais, :Enfermedades, :Tipo_sangre,
            NOW(), NOW()
        )");
        
        $stmt->execute([
            'tipo_persona' => $persona['tipo_persona'] ?? 'Nacional',
            'tipo_identificacion' => $persona['tipo_identificacion'] ?? 'Cedula',
            'numero_identificacion' => $persona['numero_identificacion'],
            'primer_nombre' => $persona['primer_nombre'],
            'segundo_nombre' => $persona['segundo_nombre'] ?? null,
            'Apellidos' => $persona['Apellidos'],
            'fecha_nacimiento' => !empty($persona['fecha_nacimiento']) ? $persona['fecha_nacimiento'] : null,
            'sexo' => !empty($persona['sexo']) ? $persona['sexo'] : null,
            'estado_civil' => $persona['estado_civil'] ?? 'Soltero',
            'email' => !empty($persona['email']) ? $persona['email'] : null,
            'telefono_principal' => $persona['telefono_principal'],
            'telefono_emergencia' => !empty($persona['telefono_emergencia']) ? $persona['telefono_emergencia'] : null,
            'direccion' => $persona['direccion'],
            'ciudad' => $persona['ciudad'] ?? 'Managua',
            'departamento_estado' => $persona['departamento_estado'] ?? 'Managua',
            'pais' => $persona['pais'] ?? 'NI',
            'Enfermedades' => !empty($persona['Enfermedades']) ? $persona['Enfermedades'] : null,
            'Tipo_sangre' => !empty($persona['Tipo_sangre']) ? $persona['Tipo_sangre'] : null,
        ]);
        
        $id_persona = $db->lastInsertId();

        // ================= 2. CREAR EMPLEADO =================
        $codigo_emp = 'EMP-' . date('Ymd') . '-' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
        
        $stmt = $db->prepare("INSERT INTO empleado (
            id_persona, codigo_emp, id_unidad, id_area, id_cargo,
            fecha_ingreso, tipo_categoria, aplica_vacaciones, estado,
            fecha_registro, fecha_modificacion
        ) VALUES (
            :id_persona, :codigo_emp, :id_unidad, :id_area, :id_cargo,
            :fecha_ingreso, :tipo_categoria, :aplica_vacaciones, :estado,
            NOW(), NOW()
        )");
        
        $stmt->execute([
            'id_persona' => $id_persona,
            'codigo_emp' => $codigo_emp,
            'id_unidad' => $empleado['id_unidad'] ?? 1,
            'id_area' => !empty($empleado['id_area']) ? $empleado['id_area'] : null,
            'id_cargo' => $empleado['id_cargo'],
            'fecha_ingreso' => $empleado['fecha_ingreso'] ?? date('Y-m-d'),
            'tipo_categoria' => $empleado['tipo_categoria'] ?? 'Administrativo',
            'aplica_vacaciones' => $empleado['aplica_vacaciones'] ?? 1,
            'estado' => $empleado['estado'] ?? 'Activo',
        ]);
        
        $id_empleado = $db->lastInsertId();

        // ================= 3. CREAR USUARIO (OPCIONAL) =================
        if (isset($_POST['crear_usuario']) && $_POST['crear_usuario'] == '1') {
            $usuario = $_POST['usuario'] ?? [];
            
            if (!empty($usuario['username']) && !empty($usuario['contraseña'])) {
                $stmt = $db->prepare("INSERT INTO usuario (
                    id_empleado, id_rol, username, contraseña, estado, fecha_registro
                ) VALUES (?, ?, ?, ?, 1, NOW())");
                
                $stmt->execute([
                    $id_empleado,
                    $usuario['id_rol'] ?? 2,
                    $usuario['username'],
                    $usuario['contraseña']
                ]);
            }
        }

        $db->commit();

        $_SESSION['mensaje'] = 'Empleado registrado correctamente';
        $_SESSION['tipo_mensaje'] = 'success';

        echo json_encode([
            'success' => true,
            'message' => 'Empleado registrado correctamente',
            'data' => [
                'id_empleado' => $id_empleado,
                'codigo_emp' => $codigo_emp
            ],
            'redirect' => 'index.php'
        ]);
        exit;

    } elseif ($accion === 'actualizar') {
        $id_empleado = $_POST['id_empleado'] ?? 0;
        
        if (!$id_empleado) {
            throw new Exception("ID de empleado no válido");
        }

        // Actualizar empleado
        $stmt = $db->prepare("UPDATE empleado SET
            id_unidad = :id_unidad,
            id_area = :id_area,
            id_cargo = :id_cargo,
            fecha_ingreso = :fecha_ingreso,
            tipo_categoria = :tipo_categoria,
            aplica_vacaciones = :aplica_vacaciones,
            estado = :estado,
            fecha_modificacion = NOW()
        WHERE id_empleado = :id_empleado");
        
        $stmt->execute([
            'id_unidad' => $empleado['id_unidad'] ?? 1,
            'id_area' => !empty($empleado['id_area']) ? $empleado['id_area'] : null,
            'id_cargo' => $empleado['id_cargo'],
            'fecha_ingreso' => $empleado['fecha_ingreso'] ?? date('Y-m-d'),
            'tipo_categoria' => $empleado['tipo_categoria'] ?? 'Administrativo',
            'aplica_vacaciones' => $empleado['aplica_vacaciones'] ?? 1,
            'estado' => $empleado['estado'] ?? 'Activo',
            'id_empleado' => $id_empleado
        ]);
        
        // Actualizar persona
        if (!empty($persona)) {
            $stmt_persona = $db->prepare("UPDATE persona SET
                tipo_persona = :tipo_persona,
                tipo_identificacion = :tipo_identificacion,
                numero_identificacion = :numero_identificacion,
                primer_nombre = :primer_nombre,
                segundo_nombre = :segundo_nombre,
                Apellidos = :Apellidos,
                fecha_nacimiento = :fecha_nacimiento,
                sexo = :sexo,
                estado_civil = :estado_civil,
                email = :email,
                telefono_principal = :telefono_principal,
                telefono_emergencia = :telefono_emergencia,
                direccion = :direccion,
                ciudad = :ciudad,
                departamento_estado = :departamento_estado,
                pais = :pais,
                fecha_modificacion = NOW()
            WHERE id_persona = (SELECT id_persona FROM empleado WHERE id_empleado = :id_empleado)");
            
            $stmt_persona->execute([
                'tipo_persona' => $persona['tipo_persona'] ?? 'Natural',
                'tipo_identificacion' => $persona['tipo_identificacion'] ?? 'Cedula',
                'numero_identificacion' => $persona['numero_identificacion'],
                'primer_nombre' => $persona['primer_nombre'],
                'segundo_nombre' => $persona['segundo_nombre'] ?? null,
                'Apellidos' => $persona['Apellidos'],
                'fecha_nacimiento' => !empty($persona['fecha_nacimiento']) ? $persona['fecha_nacimiento'] : null,
                'sexo' => !empty($persona['sexo']) ? $persona['sexo'] : null,
                'estado_civil' => $persona['estado_civil'] ?? 'Soltero',
                'email' => !empty($persona['email']) ? $persona['email'] : null,
                'telefono_principal' => $persona['telefono_principal'],
                'telefono_emergencia' => !empty($persona['telefono_emergencia']) ? $persona['telefono_emergencia'] : null,
                'direccion' => $persona['direccion'],
                'ciudad' => $persona['ciudad'] ?? 'Managua',
                'departamento_estado' => $persona['departamento_estado'] ?? 'Managua',
                'pais' => $persona['pais'] ?? 'NI',
                'id_empleado' => $id_empleado
            ]);
        }
        
        $db->commit();

        $_SESSION['mensaje'] = 'Empleado actualizado correctamente';
        $_SESSION['tipo_mensaje'] = 'success';

        echo json_encode([
            'success' => true,
            'message' => 'Empleado actualizado correctamente',
            'data' => [
                'id_empleado' => $id_empleado
            ],
            'redirect' => 'index.php'
        ]);
        exit;
    }

    throw new Exception("Acción no válida");

} catch (Exception $e) {
    if (isset($db) && $db->inTransaction()) {
        $db->rollBack();
    }
    
    error_log("Error en guardar_empleado: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
    exit;
}
?>