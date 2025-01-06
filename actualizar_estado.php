<?php
require 'db_config.php';

if (isset($_GET['id']) && isset($_GET['accion'])) {
    $id = $_GET['id'];
    $accion = $_GET['accion'];
    $fecha_actual = date("Y-m-d");

    // Consultar la petición actual
    $sql = "SELECT estado, fecha_pausa, dias_pausa, fecha_terminacion FROM peticiones WHERE id = $id";
    $result = $conn->query($sql);
    $peticion = $result->fetch_assoc();

    if ($accion === 'pausar' && $peticion['estado'] === 'activo') {
        // Pausar la petición
        $sql = "UPDATE peticiones SET estado = 'pausado', fecha_pausa = '$fecha_actual' WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Petición pausada exitosamente.'); window.location.href='lista_peticiones.php';</script>";
        } else {
            echo "<script>alert('Error: " . $conn->error . "'); window.location.href='lista_peticiones.php';</script>";
        }
    } elseif ($accion === 'reanudar' && $peticion['estado'] === 'pausado') {
        // Calcular los días de pausa
        $fecha_pausa = strtotime($peticion['fecha_pausa']);
        $dias_pausa = 0;

        while ($fecha_pausa < strtotime($fecha_actual)) {
            $fecha_pausa = strtotime("+1 day", $fecha_pausa);
            if (date("N", $fecha_pausa) < 6) { // Solo cuenta días hábiles
                $dias_pausa++;
            }
        }

        // Actualizar los días de pausa acumulados
        $dias_pausa_total = $peticion['dias_pausa'] + $dias_pausa;

        // Calcular nueva fecha de terminación
        $fecha_terminacion = calcularDiasHabiles($peticion['fecha_terminacion'], $dias_pausa);

        // Reanudar la petición
        $sql = "UPDATE peticiones 
                SET estado = 'activo', dias_pausa = $dias_pausa_total, fecha_terminacion = '$fecha_terminacion' 
                WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Petición reanudada exitosamente.'); window.location.href='lista_peticiones.php';</script>";
        } else {
            echo "<script>alert('Error: " . $conn->error . "'); window.location.href='lista_peticiones.php';</script>";
        }
    } else {
        echo "<script>alert('Acción no válida.'); window.location.href='lista_peticiones.php';</script>";
    }

    $conn->close();
}
?>