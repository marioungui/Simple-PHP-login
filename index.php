<?php
  // Include the header.php that handles the user session and initial HTML data
  if(isset($_POST['download_excel'])) {
    require_once "config.php";
  }
  else {
    require_once 'header.php';
  }
  try {
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT u.full_name, r.porcentaje_si, r.token, r.fecha_envio
              FROM users u
              INNER JOIN (
                SELECT user_id, token, MAX(fecha_registro) as fecha_envio, SUM(respuesta='si') / COUNT(*) * 100 AS porcentaje_si
                FROM respuestas
                GROUP BY user_id, token
              ) r ON u.id = r.user_id
              ORDER BY r.token DESC, r.user_id DESC
              LIMIT 20";

    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($result as &$row) {
        if ($row['porcentaje_si'] >= 0 && $row['porcentaje_si'] <= 35) {
            $row['nivel'] = 'Bajo (Mal funcionamiento)';
            $row['class'] = 'bajo';
        } elseif ($row['porcentaje_si'] > 35 && $row['porcentaje_si'] <= 70) {
            $row['nivel'] = 'Medio (Funcionamiento moderado)';
            $row['class'] = 'medio';
        } elseif ($row['porcentaje_si'] > 70 && $row['porcentaje_si'] <= 90) {
            $row['nivel'] = 'Alto (funcionamiento bueno)';
            $row['class'] = 'alto';
        } else {
            $row['nivel'] = 'Óptimo (Funcionamiento perfecto)';
            $row['class'] = 'optimo';
        }
    }
    unset($row);
} catch (PDOException $e) {
    echo "Error de conexión a la base de datos: " . $e->getMessage();
    exit();
}
?>
<style>
  .tabla-nivel-bajo {
    background-color: #cd4c4c;
    color: white;
    font-weight: bold;
  }
  .tabla-nivel-medio {
    background-color: #e1cf14;
  }
  .tabla-nivel-alto {
    background-color: #54ad72;
    color: white;
  }
  .tabla-nivel-optimo {
    background-color: #5279cf;
    color: white;
    font-weight: bold;
  }
</style>
  <div class="panel-header panel-header-lg">
    <canvas id="bigDashboardChart"></canvas>
  </div>
  <div class="content">
    <!-- <div class="row">
      <div class="col-lg-4">
        <div class="card card-chart">
          <div class="card-header">
            <h5 class="card-category">Global Sales</h5>
            <h4 class="card-title">Shipped Products</h4>
            <div class="dropdown">
              <button type="button" class="btn btn-round btn-outline-default dropdown-toggle btn-simple btn-icon no-caret" data-toggle="dropdown">
                <i class="now-ui-icons loader_gear"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="#">Action</a>
                <a class="dropdown-item" href="#">Another action</a>
                <a class="dropdown-item" href="#">Something else here</a>
                <a class="dropdown-item text-danger" href="#">Remove Data</a>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="chart-area">
              <canvas id="lineChartExample"></canvas>
            </div>
          </div>
          <div class="card-footer">
            <div class="stats">
              <i class="now-ui-icons arrows-1_refresh-69"></i> Just Updated
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="card card-chart">
          <div class="card-header">
            <h5 class="card-category">2018 Sales</h5>
            <h4 class="card-title">All products</h4>
            <div class="dropdown">
              <button type="button" class="btn btn-round btn-outline-default dropdown-toggle btn-simple btn-icon no-caret" data-toggle="dropdown">
                <i class="now-ui-icons loader_gear"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="#">Action</a>
                <a class="dropdown-item" href="#">Another action</a>
                <a class="dropdown-item" href="#">Something else here</a>
                <a class="dropdown-item text-danger" href="#">Remove Data</a>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="chart-area">
              <canvas id="lineChartExampleWithNumbersAndGrid"></canvas>
            </div>
          </div>
          <div class="card-footer">
            <div class="stats">
              <i class="now-ui-icons arrows-1_refresh-69"></i> Just Updated
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="card card-chart">
          <div class="card-header">
            <h5 class="card-category">Email Statistics</h5>
            <h4 class="card-title">24 Hours Performance</h4>
          </div>
          <div class="card-body">
            <div class="chart-area">
              <canvas id="barChartSimpleGradientsNumbers"></canvas>
            </div>
          </div>
          <div class="card-footer">
            <div class="stats">
              <i class="now-ui-icons ui-2_time-alarm"></i> Last 7 days
            </div>
          </div>
        </div>
      </div>
    </div> -->
    <div class="row">
      <!-- <div class="col-md-6">
        <div class="card  card-tasks">
          <div class="card-header ">
            <h5 class="card-category">Backend development</h5>
            <h4 class="card-title">Tasks</h4>
          </div>
          <div class="card-body ">
            <div class="table-full-width table-responsive">
              <table class="table">
                <tbody>
                  <tr>
                    <td>
                      <div class="form-check">
                        <label class="form-check-label">
                          <input class="form-check-input" type="checkbox" checked>
                          <span class="form-check-sign"></span>
                        </label>
                      </div>
                    </td>
                    <td class="text-left">Sign contract for "What are conference organizers afraid of?"</td>
                    <td class="td-actions text-right">
                      <button type="button" rel="tooltip" title="" class="btn btn-info btn-round btn-icon btn-icon-mini btn-neutral" data-original-title="Edit Task">
                        <i class="now-ui-icons ui-2_settings-90"></i>
                      </button>
                      <button type="button" rel="tooltip" title="" class="btn btn-danger btn-round btn-icon btn-icon-mini btn-neutral" data-original-title="Remove">
                        <i class="now-ui-icons ui-1_simple-remove"></i>
                      </button>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="form-check">
                        <label class="form-check-label">
                          <input class="form-check-input" type="checkbox">
                          <span class="form-check-sign"></span>
                        </label>
                      </div>
                    </td>
                    <td class="text-left">Lines From Great Russian Literature? Or E-mails From My Boss?</td>
                    <td class="td-actions text-right">
                      <button type="button" rel="tooltip" title="" class="btn btn-info btn-round btn-icon btn-icon-mini btn-neutral" data-original-title="Edit Task">
                        <i class="now-ui-icons ui-2_settings-90"></i>
                      </button>
                      <button type="button" rel="tooltip" title="" class="btn btn-danger btn-round btn-icon btn-icon-mini btn-neutral" data-original-title="Remove">
                        <i class="now-ui-icons ui-1_simple-remove"></i>
                      </button>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="form-check">
                        <label class="form-check-label">
                          <input class="form-check-input" type="checkbox" checked>
                          <span class="form-check-sign"></span>
                        </label>
                      </div>
                    </td>
                    <td class="text-left">Flooded: One year later, assessing what was lost and what was found when a ravaging rain swept through metro Detroit </td>
                    <td class="td-actions text-right">
                      <button type="button" rel="tooltip" title="" class="btn btn-info btn-round btn-icon btn-icon-mini btn-neutral" data-original-title="Edit Task">
                        <i class="now-ui-icons ui-2_settings-90"></i>
                      </button>
                      <button type="button" rel="tooltip" title="" class="btn btn-danger btn-round btn-icon btn-icon-mini btn-neutral" data-original-title="Remove">
                        <i class="now-ui-icons ui-1_simple-remove"></i>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer ">
            <hr>
            <div class="stats">
              <i class="now-ui-icons loader_refresh spin"></i> Updated 3 minutes ago
            </div>
          </div>
        </div>
      </div> -->
      <div class="col">
        <div class="card">
          <div class="card-header">
            <h5 class="card-category">Registro de valores</h5>
            <h4 class="card-title"> Últimos 20 registros</h4>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table">
                  <thead class="text-primary">
                      <th>Nombre</th>
                      <th>Porcentaje</th>
                      <th>Nivel</th>
                      <th class="text-right">Token ID:</th>
                  </thead>
                  <tbody>
                      <?php foreach ($result as $row) { ?>
                      <tr>
                          <td><?php echo $row['full_name']; ?></td>
                          <td><?php echo round($row['porcentaje_si'], 2); ?>%</td>
                          <td class="tabla-nivel-<?=$row['class']?>"><?php echo $row['nivel']; ?></td>
                          <td class="text-right"><?php echo $row['token']; ?></td>
                      </tr>
                      <?php } ?>
                  </tbody>
              </table>
            </div>
            <!-- Botón de descarga de los últimos 20 envíos en Excel -->
            <a href="download.php?action=download_last_20" class="btn btn-default btn-round"><i class="now-ui-icons arrows-1_cloud-download-93"></i> Descargar Últimos 20 Envíos</a>
            <!-- Botón de descarga de todos los envíos en Excel -->
            <a href="download.php?action=download_all" class="btn btn-default btn-round"><i class="now-ui-icons arrows-1_cloud-download-93"></i> Descargar todos los envíos</a>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php
require_once "footer.php";