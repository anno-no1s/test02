<?php

/**************************************************

	2018年1月テスト 問2プログラム

**************************************************/

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;

$reader = new XlsxReader();
$spreadsheet = $reader->load('data.xlsx');
$sheet = $spreadsheet->getSheetByName('students');

$sheet->setCellValue('C3', "名前\t");
$sheet->setCellValue('I3', '合計点');
for($i = 4; $i <= 42; $i++){
    $name = $sheet->getCell('B'.$i) . ' ' . $sheet->getCell('C'.$i);
    $total = $sheet->getCell('D'.$i)->getCalculatedValue();
    $total += $sheet->getCell('E'.$i)->getCalculatedValue();
    $total += $sheet->getCell('F'.$i)->getCalculatedValue();
    $total += $sheet->getCell('G'.$i)->getCalculatedValue();
    $total += $sheet->getCell('H'.$i)->getCalculatedValue();
    $sheet->setCellValue('C'.$i, $name);
    $sheet->setCellValue('I'.$i, $total);
}
$dataArray = $sheet->rangeToArray('C3:I42');

foreach($dataArray as $row){
    foreach($row as $cell){
        echo $cell." \t";
    }
    echo "\n";
}
