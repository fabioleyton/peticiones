<?php
require_once 'db_config.php'; // Archivo de conexión a la base de datos

// Verificar si se recibió el ID de la petición
if (!isset($_GET['id'])) {
    die('ID de petición no especificado.');
}

$id = $_GET['id'];

// Consultar los datos de la petición
$sql = "SELECT * FROM peticiones WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die('La petición no existe.');
}

$peticion = $result->fetch_assoc();

// Procesar el formulario de actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo = $_POST['codigo'];
    $tipo_pqr = $_POST['tipo_pqr'];
    $nombre = $_POST['nombre'];
    $documento = $_POST['documento'];
    $correo = $_POST['correo'];
    $fecha_entrada = $_POST['fecha_entrada'];
    $fecha_terminacion = $_POST['fecha_terminacion'];
    $estado = $_POST['estado'];

    // Actualizar los datos en la base de datos
    $sql_update = "UPDATE peticiones 
                   SET codigo = ?, tipo_pqr = ?, nombre = ?, documento = ?, correo = ?, fecha_entrada = ?, fecha_terminacion = ?, estado = ? 
                   WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param(
        'ssssssssi',
        $codigo,
        $tipo_pqr,
        $nombre,
        $documento,
        $correo,
        $fecha_entrada,
        $fecha_terminacion,
        $estado,
        $id
    );

    if ($stmt_update->execute()) {
        echo "<script>alert('Petición actualizada exitosamente.'); window.location.href = 'lista_peticiones.php';</script>";
    } else {
        echo "Error al actualizar la petición: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Petición</title>
    <link rel="stylesheet" href="styles2.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container my-5">
    <h1 class="text-center">Editar Petición</h1>
    <form method="POST">
        <div class="mb-3">
            <label for="codigo" class="form-label">Código</label>
            <input type="text" name="codigo" id="codigo" class="form-control" value="<?= htmlspecialchars($peticion['codigo']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="tipo_pqr" class="form-label">Tipo de Petición</label>
            <input type="text" name="tipo_pqr" id="tipo_pqr" class="form-control" value="<?= htmlspecialchars($peticion['tipo_pqr']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control" value="<?= htmlspecialchars($peticion['nombre']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="documento" class="form-label">Documento</label>
            <input type="text" name="documento" id="documento" class="form-control" value="<?= htmlspecialchars($peticion['documento']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="correo" class="form-label">Correo</label>
            <input type="email" name="correo" id="correo" class="form-control" value="<?= htmlspecialchars($peticion['correo']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="fecha_entrada" class="form-label">Fecha de Entrada</label>
            <input type="date" name="fecha_entrada" id="fecha_entrada" class="form-control" value="<?= htmlspecialchars($peticion['fecha_entrada']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="fecha_terminacion" class="form-label">Fecha de Terminación</label>
            <input type="date" name="fecha_terminacion" id="fecha_terminacion" class="form-control" value="<?= htmlspecialchars($peticion['fecha_terminacion']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="estado" class="form-label">Estado</label>
            <select name="estado" id="estado" class="form-select" required>
                <option value="activo" <?= $peticion['estado'] === 'activo' ? 'selected' : '' ?>>Activo</option>
                <option value="pausado" <?= $peticion['estado'] === 'pausado' ? 'selected' : '' ?>>Pausado</option>
                <option value="finalizado" <?= $peticion['estado'] === 'finalizado' ? 'selected' : '' ?>>Finalizado</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="lista_peticiones.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
