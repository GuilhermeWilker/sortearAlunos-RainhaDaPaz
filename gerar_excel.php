<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

session_start();
$salas = $_SESSION['salas'];

$spreadsheet = new Spreadsheet();

foreach ($salas as $index => $sala) {
    $sheetName = 'Sala ' . ($index + 1) . ' - ' . count($sala) . ' Alunos';
    $sheet = $spreadsheet->createSheet($index);

    $sheet->setTitle($sheetName);
    $sheet->setCellValue('A1', 'Sala ' . ($index + 1));

    $row = 2;
    foreach ($sala as $aluno) {
        $sheet->setCellValue('A' . $row, $aluno);
        $row++;
    }
}

$tempFile = tempnam(sys_get_temp_dir(), 'distribuicao_alunos');
$writer = new Xlsx($spreadsheet);
$writer->save($tempFile);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="distribuicao_alunos.xlsx"');
header('Content-Length: ' . filesize($tempFile));
readfile($tempFile);

unlink($tempFile);

exit;
