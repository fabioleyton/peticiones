<?php
// Forzar UTF-8 al inicio
header('Content-Type: text/html; charset=UTF-8');

// Incluir configuración de base de datos
require 'db_config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Peticiones</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

</head>
<body>
    <header>
    <div class="logo">
        <img src="images/logo1.png" alt="Logo">
    </div>

    </header>
    <div class="form-container">
        <h1>Formulario de Petición</h1>
        <form action="crear_peticion.php" method="post">
            <div class="form-group">
                <label for="codigo">Código</label>
                <input type="text" id="codigo" name="codigo" required>
            </div>
            <div class="form-group">
                <label for="tipo_pqr">Tipo de Petición</label>
                <select id="tipo_pqr" name="tipo_pqr" required>
                    <option value="denuncia">Denuncia</option>
                    <option value="derecho peticion">Derecho de Petición</option>
                    <option value="felicitacion">Felicitación</option>
                    <option value="queja">Queja</option>
                    <option value="reclamo">Reclamo</option>
                    <option value="sugerencia">Sugerencia</option>
                    <option value="peticion consulta">Petición de Consulta</option>
                    <option value="peticion documentos">Petición de Documentos</option>
                    <option value="peticion informacion">Petición de Información</option>
                    <option value="peticion entre autoridades">Petición entre Autoridades</option>
                    <option value="peticion por congresistas">Petición por Congresistas</option>
                </select>
            </div>
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="documento">Documento</label>
                <input type="number" id="documento" name="documento" required>
            </div>
            <div class="form-group">
                <label for="correo">Correo</label>
                <input type="email" id="correo" name="correo" required>
            </div>
            <div class="form-group">
                <label for="fecha">Fecha de Entrada</label>
                <input type="date" id="fecha" name="fecha" required>
            </div>
            <button type="submit" class="form-button">Enviar Petición</button>
        </form>
        <div class="mt-3">
            <a href="lista_peticiones.php" target="_blank" class="secondary-button">Ver Lista de Peticiones</a>
        </div>
    </div>
</body>
</html>