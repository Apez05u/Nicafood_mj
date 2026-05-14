<?php
require_once '../../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['exito' => false, 'error' => 'Método no permitido']);
    exit;
}

try {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'crear') {
        // Validaciones básicas
        $required = ['numero_identificacion', 'primer_nombre', 'Apellidos', 'telefono_principal', 'direccion'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("El campo '$field' es requerido");
            }
        }
        
        // Verificar cédula única
        $stmt = getDB()->prepare("SELECT id_persona FROM Persona WHERE numero_identificacion = ?");
        $stmt->execute([$_POST['numero_identificacion']]);
        if ($stmt->fetch()) {
            throw new Exception("Ya existe una persona con esta cédula/número de identificación");
        }
        
        // Crear persona
        $id_persona = crearPersona($_POST);
        
        echo json_encode([
            'exito' => true, 
            'id_persona' => $id_persona,
            'mensaje' => 'Persona registrada correctamente'
        ]);
        
    } elseif ($action === 'actualizar') {
        // Lógica para actualizar (similar a crear pero con UPDATE)
        $id = $_POST['id_persona'] ?? 0;
        if (!$id) {
            throw new Exception("ID de persona requerido");
        }
        
        // ... código de actualización ...
        
        echo json_encode(['exito' => true, 'mensaje' => 'Persona actualizada']);
        
    } elseif ($action === 'eliminar') {
        // Soft delete
        $id = $_POST['id'] ?? 0;
        if (!$id) {
            throw new Exception("ID requerido");
        }
        
        $stmt = getDB()->prepare("UPDATE Persona SET fecha_eliminacion = NOW() WHERE id_persona = ?");
        $stmt->execute([$id]);
        
        echo json_encode(['exito' => true, 'mensaje' => 'Persona eliminada']);
        
    } else {
        throw new Exception("Acción no válida");
    }
    
} catch (Exception $e) {
    echo json_encode(['exito' => false, 'error' => $e->getMessage()]);
}
?>