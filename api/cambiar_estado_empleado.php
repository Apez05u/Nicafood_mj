<?php
// api/cambiar_estado_empleado.php
session_start();
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

try {
    if (!isset($_SESSION['id_usuario'])) {
        throw new Exception("Sesión expirada. Inicia sesión nuevamente.");
    }

    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        throw new Exception("Datos inválidos recibidos.");
    }

    $id_empleado = $input['id_empleado'] ?? 0;
    $nuevo_estado = $input['estado'] ?? 'Inactivo';

    if (!$id_empleado) {
        throw new Exception("ID de empleado no válido");
    }

    require_once '../config/database.php';
    $db = getDB();

    $stmt = $db->prepare("UPDATE empleado SET estado = ?, fecha_modificacion = NOW() WHERE id_empleado = ?");
    $resultado = $stmt->execute([$nuevo_estado, $id_empleado]);

    if ($resultado) {
        echo json_encode([
            'success' => true,
            'message' => "Empleado {$nuevo_estado}ado correctamente",
            'nuevo_estado' => $nuevo_estado
        ]);
    } else {
        throw new Exception("No se pudo actualizar el empleado en la base de datos.");
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
exit;
?>