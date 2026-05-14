<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION['id_usuario']) || !isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$db = getDB();
$id_empleado = $_GET['id'];

try {
    // Cambiar estado a Inactivo en lugar de eliminar
    $stmt = $db->prepare("UPDATE empleado SET estado = 'Inactivo', fecha_modificacion = NOW() WHERE id_empleado = ?");
    $stmt->execute([$id_empleado]);
    
    $_SESSION['mensaje'] = 'Empleado desactivado correctamente';
    $_SESSION['tipo_mensaje'] = 'success';
} catch (Exception $e) {
    $_SESSION['mensaje'] = 'Error al desactivar empleado: ' . $e->getMessage();
    $_SESSION['tipo_mensaje'] = 'danger';
}

header("Location: index.php");
exit;
?>