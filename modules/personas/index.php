<?php
require_once '../../config/database.php';
$titulo_pagina = 'Personas';
include '../../includes/header.php';
include '../../includes/navbar.php';

// Paginación
$pagina = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limite = 20;
$offset = ($pagina - 1) * $limite;

// Obtener personas con filtro
$filtro = $_GET['q'] ?? '';
$params = [];
$sql = "SELECT * FROM Persona WHERE 1=1";

if ($filtro) {
    $sql .= " AND (primer_nombre LIKE :q OR Apellidos LIKE :q OR numero_identificacion LIKE :q)";
    $params[':q'] = "%$filtro%";
}

$sql .= " ORDER BY fecha_registro DESC LIMIT :limit OFFSET :offset";
$params[':limit'] = $limite;
$params[':offset'] = $offset;

$stmt = getDB()->prepare($sql);
$stmt->execute($params);
$personas = $stmt->fetchAll();

// Total para paginación
$stmt = getDB()->prepare("SELECT COUNT(*) FROM Persona WHERE primer_nombre LIKE :q OR Apellidos LIKE :q OR numero_identificacion LIKE :q");
$stmt->execute([':q' => "%$filtro%"]);
$total = $stmt->fetchColumn();
$paginas = ceil($total / $limite);
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-users me-2"></i>Gestión de Personas</h2>
        <a href="crear.php" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Nueva Persona
        </a>
    </div>
    
    <!-- Filtro -->
    <form method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="q" class="form-control" placeholder="Buscar por nombre, apellido o cédula..." 
                   value="<?= htmlspecialchars($filtro) ?>">
            <button class="btn btn-outline-secondary" type="submit">
                <i class="fas fa-search"></i>
            </button>
            <?php if ($filtro): ?>
            <a href="index.php" class="btn btn-outline-secondary">Limpiar</a>
            <?php endif; ?>
        </div>
    </form>
    
    <!-- Tabla de personas -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nombre Completo</th>
                            <th>Cédula</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th>Registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($personas): ?>
                            <?php foreach ($personas as $p): ?>
                            <tr>
                                <td><?= $p['id_persona'] ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($p['primer_nombre']) ?></strong> 
                                    <?= htmlspecialchars($p['segundo_nombre'] ?? '') ?>
                                    <br>
                                    <small class="text-muted"><?= htmlspecialchars($p['Apellidos']) ?></small>
                                </td>
                                <td><?= htmlspecialchars($p['numero_identificacion']) ?></td>
                                <td><?= htmlspecialchars($p['telefono_principal']) ?></td>
                                <td><?= htmlspecialchars($p['email'] ?? '-') ?></td>
                                <td><?= date('d/m/Y', strtotime($p['fecha_registro'])) ?></td>
                                <td>
                                    <a href="editar.php?id=<?= $p['id_persona'] ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-sm btn-outline-danger" 
                                            onclick="confirmarEliminar(<?= $p['id_persona'] ?>, '<?= addslashes($p['primer_nombre']) ?>')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    <i class="fas fa-info-circle me-2"></i>No se encontraron personas
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Paginación -->
    <?php if ($paginas > 1): ?>
    <nav class="mt-4">
        <ul class="pagination justify-content-center">
            <?php if ($pagina > 1): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?= $pagina - 1 ?>&q=<?= urlencode($filtro) ?>">Anterior</a>
            </li>
            <?php endif; ?>
            
            <?php for ($i = 1; $i <= $paginas; $i++): ?>
            <li class="page-item <?= $i === $pagina ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>&q=<?= urlencode($filtro) ?>"><?= $i ?></a>
            </li>
            <?php endfor; ?>
            
            <?php if ($pagina < $paginas): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?= $pagina + 1 ?>&q=<?= urlencode($filtro) ?>">Siguiente</a>
            </li>
            <?php endif; ?>
        </ul>
    </nav>
    <?php endif; ?>
</div>

<script>
function confirmarEliminar(id, nombre) {
    if (confirm(`¿Estás seguro de eliminar a "${nombre}"? Esta acción no se puede deshacer.`)) {
        fetch('eliminar.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `id=${id}`
        })
        .then(r => r.json())
        .then(data => {
            if (data.exito) {
                showToast('Persona eliminada correctamente');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(data.error || 'Error al eliminar', 'error');
            }
        })
        .catch(() => showToast('Error de conexión', 'error'));
    }
}
</script>

<?php include '../../includes/footer.php'; ?>