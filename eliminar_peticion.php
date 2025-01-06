<?php
require_once 'db_config.php';

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    
    // Preparar la consulta para eliminar la petición
    $sql = "DELETE FROM peticiones WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        // Redirigir a la lista de peticiones después de eliminar
        header("Location: lista_peticiones.php?mensaje=Petición eliminada con éxito");
        exit();
    } else {
        echo "Error al eliminar la petición: " . $conn->error;
    }
}
?>