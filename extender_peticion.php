<?php
require 'db_config.php';

$id = $_POST['id'];
$dias = $_POST['dias'];

$sql = "UPDATE peticiones SET dias_restantes = dias_restantes + ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $dias, $id);
$exito = $stmt->execute();

echo json_encode([
    'exito' => $exito,
    'mensaje' => $exito ? 'Tiempo de la petición extendido exitosamente.' : 'Error al extender el tiempo de la petición.'
]);
