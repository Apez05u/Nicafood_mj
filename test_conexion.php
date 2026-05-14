<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=nicafood_erp;charset=utf8mb4", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Conexión exitosa a la base de datos nicafood_erp";
    
    // Verificar tablas
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM information_schema.tables WHERE table_schema = 'nicafood_erp'");
    $result = $stmt->fetch();
    echo "<br>📊 Total de tablas: " . $result['total'];
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>