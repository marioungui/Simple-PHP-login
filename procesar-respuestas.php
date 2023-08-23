<?php
// Incluir archivo de configuración
require 'config.php';
session_start();

try {
    // Crear una nueva conexión PDO
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASSWORD,[PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = 'America/Asuncion'"]);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Obtener el ID del usuario de la sesión
        $userId = $_SESSION['user_id'];

        // Generar un token único
        $token = uniqid();

        foreach ($_POST as $preguntaId => $respuesta) {
            // Insertar la respuesta en la base de datos
            $queryInsert = "INSERT INTO respuestas (token, pregunta_id, respuesta, user_id) VALUES (:token, :pregunta_id, :respuesta, :user_id)";
            $stmtInsert = $conn->prepare($queryInsert);
            $stmtInsert->bindParam(':token', $token, PDO::PARAM_STR);
            $stmtInsert->bindParam(':pregunta_id', $preguntaId, PDO::PARAM_INT);
            $stmtInsert->bindParam(':respuesta', $respuesta, PDO::PARAM_STR);
            $stmtInsert->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmtInsert->execute();
        }

        // Calcular el porcentaje de respuestas "Si"
        $queryPorcentaje = "SELECT SUM(CASE WHEN respuesta = 'si' THEN 1 ELSE 0 END) / COUNT(*) * 100 AS porcentaje_si FROM respuestas WHERE token = :token";
        $stmtPorcentaje = $conn->prepare($queryPorcentaje);
        $stmtPorcentaje->bindParam(':token', $token, PDO::PARAM_STR);
        $stmtPorcentaje->execute();
        $porcentajeSi = $stmtPorcentaje->fetchColumn();

        // Respuesta JSON
        $response = array(
            "status" => "success",
            "message" => "Datos insertados exitosamente.",
            "token" => $token,
            "porcentaje_si" => $porcentajeSi
        );

        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }
} catch (PDOException $e) {
    $response = array(
        "status" => "error",
        "message" => "Error de conexión a la base de datos: " . $e->getMessage()
    );

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
?>
