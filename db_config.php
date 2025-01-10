<?php

date_default_timezone_set('America/Bogota');

$host = "localhost";
$user = "root";
$pass = "joel231903";
$db = "peticiones_db";

$conn = new mysqli($host, $user, $pass, $db);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}


// Función para calcular días hábiles, considerando sábados, domingos y festivos
if (!function_exists('calcularDiasHabiles')) {
    function calcularDiasHabiles($fecha_inicio, $dias_habiles) {
        global $conn; // Usamos la variable $conn global para la conexión a la base de datos.
        $fecha_actual = strtotime($fecha_inicio);
        $dias_agregados = 0;

        // Consultar los festivos desde la base de datos
        $sql_festivos = "SELECT fecha FROM festivos"; // Seleccionamos todas las fechas de festivos
        $result_festivos = $conn->query($sql_festivos);
        $festivos = [];

        // Guardamos las fechas de los festivos en un arreglo
        while ($row = $result_festivos->fetch_assoc()) {
            $festivos[] = $row['fecha'];  // Guardamos la fecha de cada festivo en el arreglo
        }

        // Calculamos los días hábiles
        while ($dias_agregados < $dias_habiles) {
            // Sumamos un día
            $fecha_actual = strtotime("+1 day", $fecha_actual);
            
            // Formateamos la fecha a 'Y-m-d' para compararla con las fechas de los festivos
            $fecha_formateada = date("Y-m-d", $fecha_actual);
            
            // Si es sábado (6), domingo (0), o si la fecha está en la lista de festivos, no contamos ese día
            if (date("N", $fecha_actual) < 6 && !in_array($fecha_formateada, $festivos)) {
                $dias_agregados++;
            }
        }

        // Retornamos la fecha final ajustada (día hábil calculado)
        return date("Y-m-d", $fecha_actual);
    }
}

if (!function_exists('calcularDiasRestantes')) {
    function calcularDiasRestantes($fecha_terminacion) {
        $fecha_actual = strtotime(date("Y-m-d"));  // Fecha actual
        $fecha_terminacion = strtotime($fecha_terminacion);  // Fecha de terminación
        
        // Calculamos la diferencia en segundos
        $diferencia = $fecha_terminacion - $fecha_actual;

        // Convertimos los segundos a días
        $dias_restantes = ceil($diferencia / (60 * 60 * 24));

        return $dias_restantes;
    }
}
