<?php
// Incluir el archivo de configuración de la base de datos
include('db_config.php');

// Verificar si el ID es válido
if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $id = $_POST['id'];

    // Actualizar el estado a "resuelto"
    $query = "UPDATE peticiones SET estado = 'resuelto' WHERE id = $id";
    
    // Usar el objeto $conn para ejecutar la consulta
    $result = $conn->query($query); // Usamos $conn, el cual está configurado en db_config.php

    if ($result) {
        // Redirigir con mensaje de éxito
        header("Location: lista_peticiones.php");
        exit();
    } else {
        // Redirigir con mensaje de error
        header("Location: lista_peticiones.php?mensaje=error");
        exit();
    }
} else {
    // Si no se pasa un ID válido
    header("Location: lista_peticiones.php?mensaje=id_invalido");
    exit();
}
?>
