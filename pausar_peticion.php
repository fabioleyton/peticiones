<?php
require 'db_config.php';

$id = $_POST['id'];

// Obtener la fecha de inicio y tiempo restante de la petición
$sql = "SELECT fecha_inicio, tiempo_restante_segundos FROM peticiones WHERE id = $id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$fecha_inicio = $row['fecha_inicio'];
$tiempo_restante_segundos = $row['tiempo_restante_segundos'];

// Calcular el tiempo transcurrido desde la fecha de inicio
$tiempo_transcurrido = time() - strtotime($fecha_inicio);

// Calcular el nuevo tiempo restante
$nuevo_tiempo_restante = $tiempo_restante_segundos - $tiempo_transcurrido;

// Actualizar la petición con el tiempo restante en segundos y cambiar el estado a "pausada"
$sql_update = "UPDATE peticiones 
               SET tiempo_restante_segundos = $nuevo_tiempo_restante, 
                   estado = 'pausada' 
               WHERE id = $id";

if ($conn->query($sql_update) === TRUE) {
    echo json_encode(['exito' => true]);
} else {
    echo json_encode(['exito' => false, 'mensaje' => $conn->error]);
}
?>
