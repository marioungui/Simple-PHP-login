<?php
// Incluir archivo de configuración
require 'header.php';

// Inicializar la conexión a la base de datos
$conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASSWORD);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Obtener el parámetro "form-id" de la URL
$formId = $_GET['form-id'];

// Consulta para obtener los resultados del cuestionario
$queryResultados = "SELECT * FROM respuestas WHERE token = :form_id";
$stmtResultados = $conn->prepare($queryResultados);
$stmtResultados->bindParam(':form_id', $formId, PDO::PARAM_STR);
$stmtResultados->execute();
$resultados = $stmtResultados->fetchAll(PDO::FETCH_ASSOC);

// Calcular el porcentaje de respuestas "Si"
$queryPorcentaje = "SELECT COUNT(*) as total, SUM(respuesta='si') as si_count FROM respuestas WHERE token = :form_id";
$stmtPorcentaje = $conn->prepare($queryPorcentaje);
$stmtPorcentaje->bindParam(':form_id', $formId, PDO::PARAM_STR);
$stmtPorcentaje->execute();
$porcentajeRow = $stmtPorcentaje->fetch(PDO::FETCH_ASSOC);

$porcentajeSi = 0;
if ($porcentajeRow['total'] > 0) {
    $porcentajeSi = ($porcentajeRow['si_count'] / $porcentajeRow['total']) * 100;
}
?>
    <style>
        .respuestasi {
            background-color:darkgreen;
            color: white;
        }
        .respuestano {
            background-color:#971a1a;
            color:white;
        }
    </style>
    <div class="panel-header panel-header-lg">
        <canvas id="bigDashboardChart"></canvas>
    </div>
    <div class="content mt-5">
        <div class="card">
            <div class="card-body">
                <div class="container">
                    <h1>Resultados del Cuestionario</h1>
                    <table class="table">
                        <tr>
                            <th>Pregunta</th>
                            <th class="text-center">Respuesta</th>
                        </tr>
                        <?php foreach ($resultados as $resultado) { ?>
                            <?php
                            // Obtener el texto de la pregunta en base al id de la pregunta
                            $queryPregunta = "SELECT pregunta FROM preguntas WHERE id = :pregunta_id";
                            $stmtPregunta = $conn->prepare($queryPregunta);
                            $stmtPregunta->bindParam(':pregunta_id', $resultado['pregunta_id'], PDO::PARAM_INT);
                            $stmtPregunta->execute();
                            $pregunta = $stmtPregunta->fetchColumn();
                            ?>

                            <tr>
                                <td><?php echo $pregunta; ?></td>
                                <td class="text-center respuesta<?=$resultado['respuesta'];?>"><?php echo $resultado['respuesta']; ?></td>
                            </tr>
                        <?php } ?>
                    </table>
                    <p>&nbsp;</p>
                    <p><strong>Porcentaje de respuestas "Si":</strong> <?php echo round($porcentajeSi, 2); ?>%</p>
                </div>
            </div>
        </div>
    </div>

<?php require "footer.php";?>
