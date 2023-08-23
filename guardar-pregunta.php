<?php
// Incluye el archivo de configuración
require 'config.php';

$response = array();

try {
    // Crea una nueva conexión PDO
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Recibe los datos del formulario
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $pregunta = $_POST["pregunta"];
        $parametro_id = $_POST["parametro"];

        // Inserta la pregunta en la base de datos utilizando una consulta preparada
        $stmt = $conn->prepare("INSERT INTO preguntas (pregunta, parametro_id) VALUES (:pregunta, :parametro_id)");
        $stmt->bindParam(':pregunta', $pregunta);
        $stmt->bindParam(':parametro_id', $parametro_id);
        
        if ($stmt->execute()) {
            $response["status"] = "success";
            $response["message"] = "Pregunta guardada exitosamente.";
        } else {
            $response["status"] = "error";
            $response["message"] = "Error al guardar la pregunta.";
        }
    }
} catch (PDOException $e) {
    $response["status"] = "error";
    $response["message"] = "Error de conexión a la base de datos: " . $e->getMessage();
}

// Cierra la conexión
$conn = null;

// Devuelve la respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
