<?php
require "header.php";

try {
    // Crear una nueva conexión PDO
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta para obtener los parámetros de la base de datos
    $queryParametros = "SELECT id, nombre_parametro FROM parametros";
    $stmtParametros = $conn->prepare($queryParametros);
    $stmtParametros->execute();

    // Array para almacenar los parámetros
    $parametros = $stmtParametros->fetchAll(PDO::FETCH_ASSOC);
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
                    <form id="encuesta-form" method="post" action="procesar-respuestas.php">
                        <?php foreach ($parametros as $parametro) { ?>
                        <div class="row">
                            <h4><?php echo $parametro['nombre_parametro']; ?>:</h4>
                        </div>
                        <?php
                            // Consulta para obtener las preguntas del parámetro actual
                            $queryPreguntas = "SELECT id, pregunta FROM preguntas WHERE parametro_id = :parametro_id";
                            $stmtPreguntas = $conn->prepare($queryPreguntas);
                            $stmtPreguntas->bindParam(':parametro_id', $parametro['id'], PDO::PARAM_INT);
                            $stmtPreguntas->execute();
                            
                            // Almacena las preguntas en un array asociativo (id => pregunta)
                            $preguntas = $stmtPreguntas->fetchAll(PDO::FETCH_KEY_PAIR);
                            
                            foreach ($preguntas as $preguntaId => $pregunta) {
                        ?>
                        <div class="row question">
                            <div class="clearfix"></div>
                            <div class="col-12 col-sm-6 offset-sm-1 border-b-pregunta">
                                <label for="pregunta<?php echo $preguntaId; ?>"><?php echo $parametro['nombre_parametro']; ?> - Pregunta <?php echo $preguntaId; ?>:</label>
                                <p><?php echo $pregunta; ?></p>
                            </div>
                            <div class="col-6 col-sm-2 border-b-si">
                                <div class="form-check">
                                    <input required class="form-check-input" type="radio" name="<?php echo $preguntaId; ?>" id="p<?php echo $preguntaId; ?>-si" value="si">
                                    <label class="form-check-label" for="p<?php echo $preguntaId; ?>-si">
                                        Sí
                                    </label>
                                </div>
                            </div>
                            <div class="col-6 col-sm-2 border-b-no">
                                <div class="form-check">
                                    <input required class="form-check-input" type="radio" name="<?php echo $preguntaId; ?>" id="p<?php echo $preguntaId; ?>-no" value="no">
                                    <label class="form-check-label" for="p<?php echo $preguntaId; ?>-no">
                                        No
                                    </label>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                        <?php } ?>
                        <button type="submit" class="btn btn-primary mt-3">Enviar</button>
                    </form>
                    <div id="response">
                        <p id="porcentaje-info"></p>
                        <table id="tabla-porcentaje" class="table" style="display: none;">
                            <tr>
                                <th>Nivel</th>
                                <th>Rango</th>
                            </tr>
                            <tr class="table-danger">
                                <td>Bajo (Mal funcionamiento)</td>
                                <td>0-35%</td>
                            </tr>
                            <tr class="table-warning">
                                <td>Medio (Funcionamiento moderado)</td>
                                <td>36-70%</td>
                            </tr>
                            <tr class="table-info">
                                <td>Alto (Funcionamiento bueno)</td>
                                <td>71-90%</td>
                            </tr>
                            <tr class="table-success">
                                <td>Óptimo (Funcionamiento perfecto)</td>
                                <td>91-100%</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        p#porcentaje-info {
            text-align: center;
        }
        div#answer {
            text-align: center;
            font-size: 3rem;
            font-weight: 900;
        }
        .row.question {
            padding: 15px;
        }
        .row.question:nth-child(even) {
            background: #eee;
        }
        .border-b-si label, .border-b-no label {
            color: white !important;
        }
        @media (min-width:576px) {
            .border-b-pregunta {
                border-bottom: 1px solid #d9d9d9;
            }
            .border-b-si {
                border-bottom: 1px solid #638b66;
                background: #638b66;
            }
            .border-b-no {
                border-bottom: 1px solid #dd6565;
                background: #dd6565;
            } 
        }
        @media (max-width:575px) {
            .border-b-pregunta {
                border-bottom: 1px solid #d9d9d9;
            }
            .border-b-si {
                padding-bottom:5px;
                background: #638b66;
            }
            .border-b-no {
                padding-bottom:5px;
                background: #dd6565;
            }
        }
        
    </style>
<?php
require "footer.php";
?>