<?php
require_once 'db_config.php';

// Consultar todas las peticiones activas
$sql = "SELECT id, dias_restantes, fecha_terminacion FROM peticiones WHERE estado = 'activo'";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $id = $row['id'];
    $dias_restantes = $row['dias_restantes'];
    $fecha_terminacion = $row['fecha_terminacion'];
    $fecha_hoy = date("Y-m-d");

    if ($dias_restantes > 0) {
        // Reducir días restantes en 1 si es día hábil
        if (date("N", strtotime($fecha_hoy)) < 6 && !esFestivo($fecha_hoy)) {
            $dias_restantes--;
        }

        // Verificar si la petición debe cambiar a "completada"
        $nuevo_estado = ($dias_restantes <= 0) ? 'completada' : 'activo';

        // Actualizar los días restantes y estado en la base de datos
        $sql_update = "UPDATE peticiones SET dias_restantes = $dias_restantes, estado = '$nuevo_estado' WHERE id = $id";
        $conn->query($sql_update);
    }
}

// Función para verificar si un día es festivo
function esFestivo($fecha) {
    global $conn;
    $sql = "SELECT fecha FROM festivos WHERE fecha = '$fecha'";
    $result = $conn->query($sql);
    return $result->num_rows > 0;
}

$conn->close();
?>
