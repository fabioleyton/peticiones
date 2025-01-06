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
    <div class="container">
        <h1>Formulario de Petición</h1>
        <form action="crear_peticion.php" method="post">
            <div class="mb-3">
                <label for="codigo" class="form-label">Código</label>
                <input type="text" id="codigo" name="codigo" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="tipo_pqr" class="form-label">Tipo de Petición</label>
                <select id="tipo_pqr" name="tipo_pqr" class="form-control" required>
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
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" id="nombre" name="nombre" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="documento" class="form-label">Documento</label>
                <input type="number" id="documento" name="documento" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="correo" class="form-label">Correo</label>
                <input type="email" id="correo" name="correo" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="fecha" class="form-label">Fecha de Entrada</label>
                <input type="date" id="fecha" name="fecha" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Enviar Petición</button>
        </form>
        <div class="text-center mt-4">
            <a href="lista_peticiones.php" target="_blank" class="btn btn-info w-100">Ver Lista de Peticiones</a>
        </div>
    </div>
</body>
</html>
