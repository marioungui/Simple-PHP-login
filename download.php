<?php
require 'config.php';
require 'vendor/autoload.php'; // Carga la librería PhpSpreadsheet

/**
 * Obtiene los últimos envíos de la base de datos
 * @param mixed $limit
 * @return array
 */
function obtenerUltimosEnvios($limit = null) {
    try {
        // Crear una nueva conexión PDO
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Consulta SQL para obtener los últimos envíos
        $query = "SELECT u.full_name, r.porcentaje_si, r.token, r.fecha_envio
        FROM users u
        INNER JOIN (
          SELECT user_id, token, MAX(fecha_registro) as fecha_envio, SUM(respuesta='si') / COUNT(*) * 100 AS porcentaje_si
          FROM respuestas
          GROUP BY user_id, token
        ) r ON u.id = r.user_id
        ORDER BY r.token DESC, r.user_id DESC";
        
        if ($limit !== null) {
            $query .= " LIMIT :limit";
        }

        $stmt = $conn->prepare($query);

        if ($limit !== null) {
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        }

        $stmt->execute();

        // Obtener los resultados como un array asociativo
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Agregar el nivel de respuestas "Si" a cada fila del resultado
        foreach ($result as &$row) {
            if ($row['porcentaje_si'] >= 0 && $row['porcentaje_si'] <= 35) {
                $row['nivel'] = 'Bajo (Mal funcionamiento)';
            } elseif ($row['porcentaje_si'] > 35 && $row['porcentaje_si'] <= 70) {
                $row['nivel'] = 'Medio (Funcionamiento moderado)';
            } elseif ($row['porcentaje_si'] > 70 && $row['porcentaje_si'] <= 90) {
                $row['nivel'] = 'Alto (funcionamiento bueno)';
            } else {
                $row['nivel'] = 'Óptimo (Funcionamiento perfecto)';
            }
        }

        return $result;
    } catch (PDOException $e) {
        // Manejar errores de conexión o consulta
        echo "Error: " . $e->getMessage();
        return array(); // Devolver un array vacío en caso de error
    }
}

/**
 * Función para exportar en excel los datos de envíos
 * @param mixed $data
 * @param mixed $filename
 * @return string
 */
function exportToExcel($data, $filename) {
    require 'vendor/autoload.php'; // Carga la librería PhpSpreadsheet

    // Crear un nuevo objeto de hoja de cálculo
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();


    // Definir los encabezados de la hoja de cálculo
    $sheet->setCellValue('A1', 'Nombre');
    $sheet->setCellValue('B1', 'Porcentaje');
    $sheet->setCellValue('C1', 'Nivel');
    $sheet->setCellValue('D1', 'Token ID');
    $sheet->setCellValue('E1', 'Fecha de Registro');

    // Llenar la hoja de cálculo con los datos de los envíos
    $row = 2;
    foreach ($data as $item) {
        $sheet->setCellValue('A' . $row, $item['full_name']);
        $sheet->setCellValue('B' . $row, $item['porcentaje_si'] . '%');
        $sheet->setCellValue('C' . $row, $item['nivel']);
        $sheet->setCellValue('D' . $row, $item['token']);
        $sheet->setCellValue('E' . $row, $item['fecha_envio']);
        $row++;
    }
    
    foreach ($sheet->getColumnIterator() as $column) {
        $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
     }

    // Crear un objeto Writer para guardar el archivo Excel
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

    // Definir el nombre del archivo y guardarlo en el directorio temporal
    $filepath = sys_get_temp_dir() . '/' . $filename;
    $writer->save($filepath);

    return $filepath;
}

function descargarArchivoExcel($filename, $dataCallback) {
    $result = $dataCallback();
    $filepath = exportToExcel($result, $filename);

    // Descargar el archivo
    header('Content-Type: application/octet-stream');
    header("Content-Transfer-Encoding: Binary");
    header("Content-disposition: attachment; filename=\"" . $filename . "\"");
    readfile($filepath);
    unlink($filepath);
    exit();
}

if ($_GET['action'] === 'download_last_20') {
    descargarArchivoExcel('last_20_envios.xlsx', function() {
        return obtenerUltimosEnvios(20);
    });
} elseif ($_GET['action'] === 'download_all') {
    descargarArchivoExcel('all_envios.xlsx', function() {
        return obtenerUltimosEnvios();
    });
}
?>
