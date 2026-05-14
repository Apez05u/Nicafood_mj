<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

try {
    if (!isset($_SESSION['id_usuario'])) {
        throw new Exception("No autorizado");
    }

    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input || empty($input['items'])) {
        throw new Exception("Datos inválidos o carrito vacío");
    }

    $db = getDB();
    $db->beginTransaction();

    $persona = $input['persona'] ?? [];
    $items = $input['items'] ?? [];

    // Validar campos obligatorios
    if (empty($persona['numero_identificacion'])) throw new Exception("Número de identificación requerido");
    if (empty($persona['primer_nombre'])) throw new Exception("Primer nombre requerido");
    if (empty($persona['Apellidos'])) throw new Exception("Apellidos requeridos");
    if (empty($persona['telefono_principal'])) throw new Exception("Teléfono principal requerido");
    if (empty($persona['direccion'])) throw new Exception("Dirección requerida");

    // ================= 1. REGISTRAR PERSONA =================
    // IMPORTANTE: Contar EXACTAMENTE los signos ?
    $stmt = $db->prepare("INSERT INTO persona (
        tipo_persona, tipo_identificacion, numero_identificacion,
        primer_nombre, segundo_nombre, Apellidos, fecha_nacimiento,
        sexo, genero, orientacion_sexual, pronombres_preferidos,
        consentimiento_datos_sensibles, fecha_consentimiento,
        estado_civil, email, telefono_principal, telefono_emergencia,
        direccion, ciudad, departamento_estado, pais, Enfermedades, Tipo_sangre,
        fecha_registro, fecha_modificacion
    ) VALUES (
        ?, ?, ?,
        ?, ?, ?, ?,
        ?, ?, ?, ?, ?, ?,
        ?, ?, ?, ?,
        ?, ?, ?, ?, ?, ?,
        NOW(), NOW()
    )");
    
    // Contar: deben ser EXACTAMENTE 23 parámetros (uno por cada ?)
    $stmt->execute([
        // Línea 1: tipo_persona, tipo_identificacion, numero_identificacion
        $persona['tipo_persona'] ?? 'Nacional',                    // 1
        $persona['tipo_identificacion'] ?? 'Cedula',              // 2
        $persona['numero_identificacion'],                        // 3
        
        // Línea 2: primer_nombre, segundo_nombre, Apellidos, fecha_nacimiento
        $persona['primer_nombre'],                                // 4
        $persona['segundo_nombre'] ?? null,                       // 5
        $persona['Apellidos'],                                    // 6
        !empty($persona['fecha_nacimiento']) ? $persona['fecha_nacimiento'] : null, // 7
        
        // Línea 3: sexo, genero, orientacion_sexual, pronombres_preferidos, consentimiento, fecha
        !empty($persona['sexo']) ? $persona['sexo'] : null,       // 8
        !empty($persona['genero']) ? $persona['genero'] : null,   // 9
        !empty($persona['orientacion_sexual']) ? $persona['orientacion_sexual'] : null, // 10
        !empty($persona['pronombres_preferidos']) ? $persona['pronombres_preferidos'] : null, // 11
        !empty($persona['consentimiento_datos_sensibles']) ? 1 : 0, // 12
        !empty($persona['fecha_consentimiento']) ? $persona['fecha_consentimiento'] : null, // 13
        
        // Línea 4: estado_civil, email, telefono_principal, telefono_emergencia
        $persona['estado_civil'] ?? 'Soltero',                    // 14
        !empty($persona['email']) ? $persona['email'] : null,     // 15
        $persona['telefono_principal'],                           // 16
        !empty($persona['telefono_emergencia']) ? $persona['telefono_emergencia'] : null, // 17
        
        // Línea 5: direccion, ciudad, departamento_estado, pais, Enfermedades, Tipo_sangre
        $persona['direccion'],                                    // 18
        $persona['ciudad'] ?? 'Managua',                          // 19
        $persona['departamento_estado'] ?? 'Managua',             // 20
        $persona['pais'] ?? 'NI',                                 // 21
        !empty($persona['Enfermedades']) ? $persona['Enfermedades'] : null, // 22
        !empty($persona['Tipo_sangre']) ? $persona['Tipo_sangre'] : null   // 23
        // NOW(), NOW() son automáticos, no cuentan
    ]);
    
    $id_persona = $db->lastInsertId();

    // ================= 2. REGISTRAR CLIENTE =================
    $codigo_cliente = 'CLI-' . date('Ymd') . '-' . str_pad($id_persona, 4, '0', STR_PAD_LEFT);
    $stmt = $db->prepare("INSERT INTO cliente (id_persona, codigo_cliente, metodo_pago_preferido, fecha_registro, fecha_modificacion) VALUES (?, ?, ?, NOW(), NOW())");
    $stmt->execute([
        $id_persona,
        $codigo_cliente,
        $persona['metodo_pago_preferido'] ?? 'Efectivo'
    ]);
    $id_cliente = $db->lastInsertId();

    // ================= 3. CALCULAR TOTALES =================
    $subtotal = 0;
    foreach ($items as $item) {
        if ($item['tipo'] === 'producto' && !empty($item['id_producto']) && $item['id_producto'] > 0) {
            $subtotal += ($item['precio'] ?? 0) * ($item['cantidad'] ?? 1);
        }
    }
    
    $impuesto = $subtotal * 0.15;
    $total = $subtotal + $impuesto;
    $numero_factura = 'FAC-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

    // ================= 4. CREAR FACTURA =================
    $stmt = $db->prepare("INSERT INTO factura_cabecera (numero_factura, id_cliente, id_empleado, id_unidad, total_neto, total_impuesto, total_pagar, estado, fecha_emision) VALUES (?, ?, ?, 1, ?, ?, ?, 'Pagada', NOW())");
    $stmt->execute([
        $numero_factura,
        $id_cliente,
        $input['id_empleado_despacha'] ?? 1,
        $subtotal,
        $impuesto,
        $total
    ]);
    $id_factura = $db->lastInsertId();

    // ================= 5. INSERTAR DETALLES =================
    $stmt_detalle = $db->prepare("INSERT INTO factura_detalle (id_factura, id_producto, cantidad, precio_u, subtotal) VALUES (?, ?, ?, ?, ?)");
    
    foreach ($items as $item) {
        if ($item['tipo'] === 'producto' && !empty($item['id_producto']) && $item['id_producto'] > 0) {
            $check = $db->prepare("SELECT id_producto, precio_venta, activo FROM producto WHERE id_producto = ?");
            $check->execute([$item['id_producto']]);
            $producto = $check->fetch();
            
            if ($producto && $producto['activo'] == 1) {
                $precio = $item['precio'] ?? $producto['precio_venta'] ?? 0;
                $cantidad = $item['cantidad'] ?? 1;
                $stmt_detalle->execute([$id_factura, $item['id_producto'], $cantidad, $precio, $precio * $cantidad]);
            }
        }
    }

    $db->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Pedido registrado correctamente',
        'data' => [
            'numero_factura' => $numero_factura,
            'codigo_cliente' => $codigo_cliente,
            'total' => $total
        ]
    ]);

} catch (Exception $e) {
    if (isset($db) && $db->inTransaction()) $db->rollBack();
    error_log("Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>