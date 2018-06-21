<?php
/**
 * Created by PhpStorm.
 * User: nikita
 * Date: 20.06.2018
 * Time: 13:12
 */

namespace App;
use PHPExcel;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Fill;
use PHPExcel_Writer_Excel5;

class ExcelHelper
{
    public function __construct()
    {
    }

    /***
     * @param $sheet \PHPExcel_Worksheet
     * @throws \PHPExcel_Exception
     */
    public function initSheet($sheet)
    {
        $sheet->setTitle("Отчет");
        //header
        $sheet->getStyle('A1:E1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle('A1:E1')->getFill()->getStartColor()->setRGB('a2c4ef');
        $sheet->setCellValueExplicit('A1', "№");
        $sheet->setCellValueExplicit('B1', "Название проверки");
        $sheet->setCellValueExplicit('C1', "Статус");
        $sheet->setCellValueExplicit('E1', "Текущее состояние");
        // Объединяем ячейки
        $sheet->mergeCells('A2:E2');
        //header
//        for($i = 0; $i < 20; $i++) {
//            $sheet->getColumnDimensionByColumn($i)->setAutoSize(true);
//        } // ширина ячейки
        // Выравнивание текста
        $sheet->getStyle('A1:E1')->getAlignment()->setHorizontal(
            PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

//            for ($i = 2; $i < 10; $i++) {
//                for ($j = 2; $j < 10; $j++) {
//                    // Выводим таблицу умножения
//                    $sheet->setCellValueByColumnAndRow(
//                        $i - 2,
//                        $j,
//                        $i . "x" .$j . "=" . ($i*$j));
//                    // Применяем выравнивание
//                    $sheet->getStyleByColumnAndRow($i - 2, $j)->getAlignment()->
//                    setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//                }
//            }
    }
    
    /***
     * @param $data DataModel
     */
    public function create($data)
    {
        $xls = new PHPExcel();
        try {
            $xls->setActiveSheetIndex(0);
            $sheet = $xls->getActiveSheet();
            $this->initSheet($sheet);
            // Выводим содержимое файла
            $objWriter = new PHPExcel_Writer_Excel5($xls);
            $objWriter->save('temp//test.xls');
        } catch (\PHPExcel_Exception $e) {
            echo $e->getMessage();
        }
    }
}