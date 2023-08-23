<?php
// Incluir el archivo de configuración de la base de datos
require_once '../config.php'; // Ruta ajustada

try {
    // Crear una instancia de la clase PDO utilizando las constantes de configuración
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASSWORD
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta para obtener los últimos 10 registros y calcular el porcentaje de respuestas 'Si'
    $query = "SELECT * FROM (SELECT token, COUNT(*) as total, SUM(respuesta='si') as si_count, MAX(fecha_registro) as fecha_registro FROM respuestas GROUP BY token ORDER BY MAX(id) DESC LIMIT 10) AS subquery ORDER BY fecha_registro ASC;";
    $stmt = $pdo->query($query);
    $porcentajes = [];
    $fechas = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $porcentaje = ($row['si_count'] / $row['total']) * 100;
        $porcentajes[] = round($porcentaje, 2);
        $fechas[] = $row['fecha_registro'];
    }

    // Calcular el porcentaje promedio de las respuestas 'Si'
    $porcentajePromedio = array_sum($porcentajes) / count($porcentajes);

    // Preparar los datos para la respuesta JSON
    $response = [
        'labels' => range(1, 10),
        'porcentajes' => $porcentajes,
        'fechas' => $fechas,
        'porcentaje_promedio' => $porcentajePromedio
    ];

    // Devolver los datos en formato JSON
    header('Content-Type: application/json');
    echo json_encode($response);
} catch (PDOException $e) {
    // Manejar errores de conexión a la base de datos
    $response = [
        'error' => 'Error de conexión a la base de datos: ' . $e->getMessage()
    ];

    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
