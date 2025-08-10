<?php
require 'db_config.php';

$id = $_POST['id'];

// Obtener el tiempo restante en segundos y el estado actual de la petición
$sql = "SELECT tiempo_restante_segundos FROM peticiones WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $tiempo_restante_segundos = $row['tiempo_restante_segundos'];

    // Verificar si el tiempo restante es válido
    if ($tiempo_restante_segundos > 0) {
        // Establecer la nueva fecha de inicio como el momento actual
        $fecha_reanudacion = date('Y-m-d H:i:s');

        // Actualizar la petición para reanudarla
        $sql_update = "UPDATE peticiones 
                       SET estado = 'activa', 
                           fecha_inicio = '$fecha_reanudacion' 
                       WHERE id = $id";

        if ($conn->query($sql_update) === TRUE) {
            // Devolver la respuesta con el tiempo restante en segundos
            echo json_encode([
                "exito" => true,
                "tiempo_restante" => $tiempo_restante_segundos,
                "fecha_inicio" => $fecha_reanudacion
            ]);
        } else {
            echo json_encode([
                "exito" => false,
                "mensaje" => "Error al actualizar la petición: " . $conn->error
            ]);
        }
    } else {
        echo json_encode([
            "exito" => false,
            "mensaje" => "El tiempo restante no es válido."
        ]);
    }
} else {
    echo json_encode([
        "exito" => false,
        "mensaje" => "Petición no encontrada."
    ]);
}
?>
