<?php
require 'vendor/autoload.php'; // Necesitas PhpSpreadsheet (composer require phpoffice/phpspreadsheet)
include 'conexion.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

// Consultamos todos los tickets
$sql = "SELECT mes, fecha, area, centro_costo, quien_solicita, tema, solicitud, solucion, metodo, fecha_rta, estado FROM tickets";
$result = $conn->query($sql);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Encabezados
$headers = ["MES", "FECHA", "ÁREA", "CENTRO DE COSTO", "QUIEN SOLICITA", "TEMA", "SOLICITUD", "SOLUCIÓN", "MÉTODO", "FECHA RTA", "ESTADO"];
$col = "A";
foreach ($headers as $header) {
    $sheet->setCellValue($col."1", $header);
    $sheet->getStyle($col."1")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('00FF00');
    $sheet->getStyle($col."1")->getFont()->getColor()->setARGB('000000');
    $sheet->getStyle($col."1")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $col++;
}

// Filas con datos
$row = 2;
while ($ticket = $result->fetch_assoc()) {
    $col = "A";
    foreach ($ticket as $value) {
        $sheet->setCellValue($col.$row, $value);
        $col++;
    }
    $row++;
}

// Ajustar tamaño automático
foreach (range('A', $col) as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

// Descargar archivo
$writer = new Xlsx($spreadsheet);
$filename = "tickets_export.xlsx";

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$filename\"");
$writer->save("php://output");
exit;

