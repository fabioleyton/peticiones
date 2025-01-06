<?php
require_once 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $codigo = $_POST['codigo'];
    $tipo_pqr = $_POST['tipo_pqr'];
    $nombre = $_POST['nombre'];
    $documento = $_POST['documento'];
    $correo = $_POST['correo'];
    $fecha_entrada = $_POST['fecha'];

    // Lógica de días hábiles por tipo de petición
    switch ($tipo_pqr) {
        case 'denuncia':
        case 'derecho peticion':
        case 'felicitacion':
        case 'queja':
        case 'reclamo':
        case 'sugerencia':
            $dias_habiles = 15;
            break;
        case 'peticion consulta':
            $dias_habiles = 30;
            break;
        case 'peticion documentos':
        case 'peticion informacion':
        case 'peticion entre autoridades':
            $dias_habiles = 10;
            break;
        case 'peticion por congresistas':
            $dias_habiles = 5;
            break;
        default:
            $dias_habiles = 5;
            break;
    }

    // Calcular fecha de terminación y días restantes
    $fecha_terminacion = calcularDiasHabiles($fecha_entrada, $dias_habiles);

    // Insertar datos en la base de datos
    $sql = "INSERT INTO peticiones (tipo_pqr, nombre, documento, correo, fecha_entrada, fecha_terminacion, dias_restantes, codigo) 
            VALUES ('$tipo_pqr', '$nombre', '$documento', '$correo', '$fecha_entrada', '$fecha_terminacion', $dias_habiles, '$codigo')";
    
    if ($conn->query($sql) === TRUE) {
        // Mostrar mensaje de éxito en un alert
        echo "<script>alert('Petición registrada exitosamente.'); window.location.href='index.php';</script>";
    } else {
        // Mostrar mensaje de error en un alert
        echo "<script>alert('Error: " . $conn->error . "'); window.location.href='index.php';</script>";
    }

    $conn->close();
}
?>
