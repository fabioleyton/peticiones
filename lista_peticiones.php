<?php
require_once 'db_config.php';

// Función para asignar días según el tipo de petición
function obtenerDiasPorTipo($tipo) {
    $dias_por_tipo = [
        'denuncia' => 16,
        'derecho peticion' => 16,
        'felicitacion' => 16,
        'queja' => 16,
        'reclamo' => 16,
        'sugerencia' => 16,
        'peticion consulta' => 31,
        'peticion documentos' => 11,
        'peticion informacion' => 11,
        'peticion entre autoridades' => 11,
        'peticion por congresistas' => 6
    ];

    return $dias_por_tipo[$tipo] ?? 'N/A'; // Retorna 'N/A' si no encuentra el tipo
}

// Función para calcular los días restantes
function calcularDiasRestantes($fecha_terminacion) {
    $fecha_actual = strtotime(date("Y-m-d"));
    $fecha_fin = strtotime($fecha_terminacion);

    return ceil(($fecha_fin - $fecha_actual) / (60 * 60 * 24));
}

// Variables para filtros y búsqueda
$search = isset($_GET['search']) ? $_GET['search'] : '';
$order_by = isset($_GET['order_by']) ? $_GET['order_by'] : 'id';
$order_dir = isset($_GET['order_dir']) && $_GET['order_dir'] === 'desc' ? 'desc' : 'asc';
$valid_columns = ['id', 'codigo', 'tipo_pqr', 'nombre', 'dias_restantes'];

// Validar columna de ordenamiento
if (!in_array($order_by, $valid_columns)) {
    $order_by = 'id';
}

// Obtener datos de la base de datos con búsqueda y filtros
$sql = "SELECT *, (SELECT DATEDIFF(fecha_terminacion, CURDATE())) AS dias_restantes FROM peticiones WHERE nombre LIKE ? OR tipo_pqr LIKE ? OR codigo LIKE ? ORDER BY $order_by $order_dir LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$search_term = "%$search%";
$limit = 10;
$page = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($page - 1) * $limit;
$stmt->bind_param('ssssi', $search_term, $search_term, $search_term, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

// Contar total de registros para paginación
$sql_total = "SELECT COUNT(*) as total FROM peticiones WHERE nombre LIKE ? OR tipo_pqr LIKE ? OR codigo LIKE ?";
$stmt_total = $conn->prepare($sql_total);
$stmt_total->bind_param('sss', $search_term, $search_term, $search_term);
$stmt_total->execute();
$result_total = $stmt_total->get_result();
$total_registros = $result_total->fetch_assoc()['total'];
$total_paginas = ceil($total_registros / $limit);
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Peticiones</title>
    <link rel="stylesheet" href="styles2.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome -->

</head>
<body>
<div class="container my-5">
    <h1 class="text-center">Lista de Peticiones</h1>

    <!-- Barra de búsqueda -->
    <form class="row mb-4" method="get" action="">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Buscar por nombre, tipo o código" value="<?= htmlspecialchars($search) ?>">
        </div>
        <div class="col-md-3">
            <select name="order_by" class="form-select">
                <option value="id" <?= $order_by === 'id' ? 'selected' : '' ?>>Ordenar por ID</option>
                <option value="codigo" <?= $order_by === 'codigo' ? 'selected' : '' ?>>Ordenar por Código</option>
                <option value="tipo_pqr" <?= $order_by === 'tipo_pqr' ? 'selected' : '' ?>>Ordenar por Tipo</option>
                <option value="nombre" <?= $order_by === 'nombre' ? 'selected' : '' ?>>Ordenar por Nombre</option>
                <option value="dias_restantes" <?= $order_by === 'dias_restantes' ? 'selected' : '' ?>>Ordenar por Días Restantes</option>
            </select>
        </div>
        <div class="col-md-3">
            <select name="order_dir" class="form-select">
                <option value="asc" <?= $order_dir === 'asc' ? 'selected' : '' ?>>Ascendente</option>
                <option value="desc" <?= $order_dir === 'desc' ? 'selected' : '' ?>>Descendente</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Filtrar</button>
        </div>
    </form>

    <table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Código</th>
            <th>Tipo</th>
            <th>Nombre</th>
            <th>Documento</th>
            <th>Correo</th>
            <th>Fecha de Entrada</th>
            <th>Fecha de Terminación</th>
            <th>Días de Petición</th>
            <th>Días Restantes</th>
            <th>Fecha de Pausa</th>
            <th>Días de Pausa</th>
            <th>Estado</th>
            <th colspan="3">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): 
            $dias_restantes = $row['dias_restantes'];
            // Determinar el color de la fila
            if ($dias_restantes <= 0) {
                $row_class = 'table-danger'; // Rojo si ya terminó
            } elseif ($dias_restantes <= 2) {
                $row_class = 'table-warning'; // Amarillo si quedan 2 días o menos
            } else {
                $row_class = ''; // Sin clase si está en estado normal
            }
        ?>
            <tr class="<?= $row_class ?>">
                <td><?= $row['id'] ?></td>
                <td><?= $row['codigo'] ?></td>
                <td><?= $row['tipo_pqr'] ?></td>
                <td><?= $row['nombre'] ?></td>
                <td><?= $row['documento'] ?></td>
                <td><?= $row['correo'] ?></td>
                <td><?= $row['fecha_entrada'] ?></td>
                <td><?= $row['fecha_terminacion'] ?></td>
                <td><?= obtenerDiasPorTipo($row['tipo_pqr']) ?></td>
                <td><?= $dias_restantes > 0 ? $dias_restantes . ' días restantes' : 'Vencido' ?></td>
                <td><?= $row['fecha_pausa'] ?? 'N/A' ?></td>
                <td><?= $row['dias_pausa'] ?></td>
                <td><?= ucfirst($row['estado']) ?></td>
                <td>
                    <?php if ($row['estado'] === 'activo'): ?>
                        <a href="actualizar_estado.php?id=<?= $row['id'] ?>&accion=pausar" class="btn btn-warning btn-sm"><i class="fas fa-pause"></i></a>
                    <?php elseif ($row['estado'] === 'pausado'): ?>
                        <a href="actualizar_estado.php?id=<?= $row['id'] ?>&accion=reanudar" class="btn btn-success btn-sm"><i class="fas fa-play"></i></a>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if (!isset($row['extensiones_usadas']) || $row['extensiones_usadas'] < 1): ?>
                        <form action="extender_dias.php" method="post" class="d-inline">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <input type="number" name="dias_extra" min="1" placeholder="Días" required class="form-control form-control-sm mb-1">
                            <button type="submit" class="btn btn-primary btn-sm">Extender</button>
                        </form>
                    <?php else: ?>
                        <button class="btn btn-secondary btn-sm" disabled>Extensión Usada</button>
                    <?php endif; ?>
                </td>
                <td>
                    <form action="eliminar_peticion.php" method="post" class="d-inline">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
                <td>
                    <a href="editar_peticion.php?id=<?= $row['id'] ?>" class="btn btn-info btn-sm">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

    <!-- Paginación -->
    <div class="pagination">
        <ul class="pagination">
            <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">
                <a class="page-link" href="?pagina=1&search=<?= $search ?>&order_by=<?= $order_by ?>&order_dir=<?= $order_dir ?>">Primera</a>
            </li>
            <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">
                <a class="page-link" href="?pagina=<?= $page - 1 ?>&search=<?= $search ?>&order_by=<?= $order_by ?>&order_dir=<?= $order_dir ?>">Anterior</a>
            </li>
            <li class="page-item <?= $page == $total_paginas ? 'disabled' : '' ?>">
                <a class="page-link" href="?pagina=<?= $page + 1 ?>&search=<?= $search ?>&order_by=<?= $order_by ?>&order_dir=<?= $order_dir ?>">Siguiente</a>
            </li>
            <li class="page-item <?= $page == $total_paginas ? 'disabled' : '' ?>">
                <a class="page-link" href="?pagina=<?= $total_paginas ?>&search=<?= $search ?>&order_by=<?= $order_by ?>&order_dir=<?= $order_dir ?>">Última</a>
            </li>
        </ul>
    </div>
</div>
</div>
</body>
</html>

