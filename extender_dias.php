<?php
require_once 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $dias_extra = intval($_POST['dias_extra']);

    if ($dias_extra > 0) {
        // Obtener datos actuales de la petición
        $sql = "SELECT fecha_terminacion, extensiones_usadas FROM peticiones WHERE id = $id";
        $result = $conn->query($sql);
        
        if ($result) {
            $peticion = $result->fetch_assoc();

            if ($peticion) {
                // Verificar si ya se ha usado la extensión
                if ($peticion['extensiones_usadas'] >= 1) {
                    echo "<script>alert('La opción de extender días ya ha sido utilizada.'); window.location.href='lista_peticiones.php';</script>";
                    exit();
                }

                $fecha_terminacion_actual = $peticion['fecha_terminacion'];

                // Calcular nueva fecha de terminación
                $nueva_fecha_terminacion = calcularDiasHabiles($fecha_terminacion_actual, $dias_extra);

                // Actualizar en la base de datos
                $sql_update = "UPDATE peticiones 
                               SET fecha_terminacion = '$nueva_fecha_terminacion', 
                                   extensiones_usadas = extensiones_usadas + 1 
                               WHERE id = $id";
                if ($conn->query($sql_update) === TRUE) {
                    echo "<script>alert('Días extendidos exitosamente.'); window.location.href='lista_peticiones.php';</script>";
                } else {
                    echo "<script>alert('Error al extender días: " . $conn->error . "'); window.location.href='lista_peticiones.php';</script>";
                }
            } else {
                echo "<script>alert('Petición no encontrada.'); window.location.href='lista_peticiones.php';</script>";
            }
        } else {
            echo "<script>alert('Error al ejecutar la consulta: " . $conn->error . "'); window.location.href='lista_peticiones.php';</script>";
        }
    } else {
        echo "<script>alert('Debe agregar un número válido de días.'); window.location.href='lista_peticiones.php';</script>";
    }
}

$conn->close();
?>