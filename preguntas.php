<?php
require "header.php";

try {
    // Crear una nueva conexión PDO
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta para obtener las preguntas de la base de datos
    $query = "SELECT pregunta FROM preguntas";
    $stmt = $conn->prepare($query);
    $stmt->execute();

    // Almacena las preguntas en un array
    $preguntas = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    echo "Error de conexión a la base de datos: " . $e->getMessage();
}

?>
    <div class="panel-header panel-header-lg">
        <canvas id="bigDashboardChart"></canvas>
    </div>
    <div class="content">
        <div class="card">
            <div class="card-body">
                <div class="container">
                    <h2>Formulario de Preguntas</h2>
                    <p>Creá acá tus preguntas para comenzar a registrar:</p>
                    <form action="guardar-pregunta.php" id="preguntaForm" method="post">
                        <div class="form-group">
                            <label for="pregunta">Pregunta:</label>
                            <textarea class="form-control" name="pregunta" id="pregunta" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="parametro">Parámetro:</label>
                            <select class="form-control" name="parametro" id="parametro" required>
                                <option value="" disabled selected>Selecciona un parámetro</option>
                                <!-- Aquí deberías generar las opciones dinámicamente desde la base de datos -->
                                <option value="1">Instalaciones</option>
                                <option value="2">Personal</option>
                                <option value="3">Materia Prima</option>
                                <option value="4">Documentación</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar Pregunta</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<style>
    .border-b-pregunta {
        border-bottom: 1px solid #d9d9d9;
    }
    .border-b-si {
        border-bottom: 1px solid #638b66;
    }
    .border-b-no {
        border-bottom: 1px solid #dd6565;
    }
</style>
<?php
require "footer.php";
?>