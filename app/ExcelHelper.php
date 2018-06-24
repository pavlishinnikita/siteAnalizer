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
     * @var $data DataModel
     * @throws \PHPExcel_Exception
     */
    public function initSheet($sheet, $data)
    {
        $sheet->setTitle("Отчет");
        //header
        $sheet->getStyle('A1:E1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle('A1:E1')->getFill()->getStartColor()->setRGB('a2c4ef');
        $sheet->getColumnDimension('A')->setWidth(8.43);
        $sheet->getColumnDimension('B')->setWidth(52.71);
        $sheet->getColumnDimension('C')->setWidth(11.43);
        $sheet->getColumnDimension('D')->setWidth(14.43);
        $sheet->getColumnDimension('E')->setWidth(64.86);
        $sheet->setCellValueExplicit('A1', "№");
        $sheet->setCellValueExplicit('B1', "Название проверки");
        $sheet->setCellValueExplicit('C1', "Статус");
        $sheet->setCellValueExplicit('E1', "Текущее состояние");
        // Объединяем ячейки
        $sheet->mergeCells('A2:E2');
        //header

        // Выравнивание текста
        $sheet->getStyle('A1:E1')->getAlignment()->setHorizontal(
            PHPExcel_Style_Alignment::HORIZONTAL_CENTER_CONTINUOUS);
        for($i = 0; $i < 5; $i++) { // столбцы
            $counterForMerge = 0;
            $counterForMergeCell = 0;
            $test = true;
            for ($j = 3; $j < 20; $j++) { //строки
                $counterForMerge++;
                $counterForMergeCell++;
                if($counterForMerge%3 == 0) { // делаю полоску
                    $counterForMergeCell = 0;
                    if($i != 4) {
                        $sheet->mergeCellsByColumnAndRow($i, $j, $i + 1, $j);
                    }
                    continue;
                }
                switch ($i) {
                    case 0:
                        $sheet->setCellValueByColumnAndRow($i, $j, "$data->file_size");
                        break;
                    case 1:
                        $sheet->setCellValueByColumnAndRow($i, $j, self::ROBOTS_EXIST_TEXT);
                        break;
                    case 2:
                        if($data->file_exist) {
                            $color = '00ff00';
                            $status = 'OK';
                        } else {
                            $color = 'ff0000';
                            $status = 'Ошибка';
                        }
                        $sheet->getStyleByColumnAndRow($i, $j, $i, $j)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                        $sheet -> getStyleByColumnAndRow($i, $j, $i, $j)->getFill()->getStartColor()->setRGB($color);
                        $sheet->setCellValueByColumnAndRow($i, $j, $status);
                        break;
                    case 3:
                        if($test == true) {
                            $sheet->setCellValueByColumnAndRow($i, $j, "Состояние");
                            $test = false;
                        } else {
                            $sheet->setCellValueByColumnAndRow($i, $j, "Рекомендация");
                            $test = true;
                        }
                        break;
                    case 4:
                        if($data->file_exist) {
                            $textStatus = 'Файл robots.txt присутствует';
                            $textRecommendations = "Доработки не требуются";
                        } else {
                            $textStatus = 'Файл robots.txt отсутствует';
                            $textRecommendations = "Программист: Создать файл robots.txt и разместить его на сайте.";
                        }
                        if($test == true) {
                            $sheet->setCellValueByColumnAndRow($i, $j, $textStatus);
                            $test = false;
                        } else {
                            $sheet->setCellValueByColumnAndRow($i, $j, $textRecommendations);
                            $test = true;
                        }
                        break;
                }
                if($counterForMergeCell%2 != 0) {
                    if($i != 3 && $i != 4) {
                        $sheet->mergeCellsByColumnAndRow($i, $j, $i, $j + 1);
                        continue;
                    }
                }
            }
        }
    }

    /***
     * @param $sheet \PHPExcel_Worksheet
     * @throws \PHPExcel_Exception
     */
    private function fill_task($sheet, $line, $text, $status, $color = null, $state, $recomendation, $no)
    {
        for ($i = 0; $i < 5; $i++) {
            $test = true;
            for ($j = $line; $j <= $line + 2; $j++) { // столбцы, 3 штуки, 2 обычных и полоска
                switch ($i) {
                    case 0:
                        if($j != ($line + 2)) {
                            $sheet->setCellValueByColumnAndRow($i, $j, $no);
                        }
                        break;
                    case 1:
                        $sheet->setCellValueByColumnAndRow($i, $j, $text);
                        break;
                    case 2:
                        $sheet->getStyleByColumnAndRow($i, $j, $i, $j)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                        $sheet -> getStyleByColumnAndRow($i, $j, $i, $j)->getFill()->getStartColor()->setRGB($color);
                        $sheet->setCellValueByColumnAndRow($i, $j, $status);
                        break;
                    case 3:
                        if($test == true) {
                            $sheet->setCellValueByColumnAndRow($i, $j, "Состояние");
                            $test = false;
                        } else {
                            $sheet->setCellValueByColumnAndRow($i, $j, "Рекомендация");
                            $test = true;
                        }
                        break;
                    case 4:
                        if($test == true) {
                            $sheet->setCellValueByColumnAndRow($i, $j, $state);
                            $test = false;
                        } else {
                            $sheet->setCellValueByColumnAndRow($i, $j, $recomendation);
                            $test = true;
                        }
                        break;
                }
                if($j == $line && ($i < 3)) {
                    $sheet->mergeCellsByColumnAndRow($i, $j, $i, $j + 1);
                }
                if($j == $line + 2) { // пилю линию
                    $sheet->mergeCellsByColumnAndRow($i, $j, $i + 1, $j);
                }
            }
        }
    }

    /***
     * @param $sheet \PHPExcel_Worksheet
     * @var $data DataModel
     * @throws \PHPExcel_Exception
     */
    public function initSheet2($sheet, $data)
    {
        $sheet->setTitle("Отчет");
        //header
        $sheet->getStyle('A1:E1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle('A1:E1')->getFill()->getStartColor()->setRGB('a2c4ef');
        $sheet->getColumnDimension('A')->setWidth(8.43);
        $sheet->getColumnDimension('B')->setWidth(52.71);
        $sheet->getColumnDimension('C')->setWidth(11.43);
        $sheet->getColumnDimension('D')->setWidth(14.43);
        $sheet->getColumnDimension('E')->setWidth(64.86);
        $sheet->setCellValueExplicit('A1', "№");
        $sheet->setCellValueExplicit('B1', "Название проверки");
        $sheet->setCellValueExplicit('C1', "Статус");
        $sheet->setCellValueExplicit('E1', "Текущее состояние");
        // Объединяем ячейки
        $sheet->mergeCells('A2:E2');
        //header
        // Выравнивание текста
        $sheet->getStyle('A1:E1')->getAlignment()->setHorizontal(
            PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        for($k = 0; $k < 6; $k++) { // колво проверок
            $y = 3 * $k + 3;
            switch ($k) {
                case 0:
                    if($data->file_exist) {
                        $status = WordConstant::STATUS_OK;
                        $color = WordConstant::COLOR_OK;
                        $state = "Файл robots.txt присутствует";
                        $textRecommendations = "Доработки не требуются";
                    } else {
                        $status = WordConstant::STATUS_FAIL;
                        $color = WordConstant::COLOR_FAIL;
                        $state = 'Файл robots.txt отсутствует';
                        $textRecommendations = "Программист: Создать файл robots.txt и разместить его на сайте.";
                    }
                    $this->fill_task($sheet,$y, WordConstant::ROBOTS_EXIST_TEXT, $status, $color, $state,
                        $textRecommendations, 1);
                    break;
                case 1:
                    if($data->host_exist) {
                        $status = WordConstant::STATUS_OK;
                        $color = WordConstant::COLOR_OK;
                        $state = "Директива Host указана";
                        $textRecommendations = "Доработки не требуются";
                    } else {
                        $status = WordConstant::STATUS_OK;
                        $color = WordConstant::COLOR_FAIL;
                        $state = WordConstant::HOST_ERROR_STATE_TEXT;
                        $textRecommendations = "Программист: Для того, чтобы поисковые системы знали, какая версия сайта является основных зеркалом, необходимо прописать адрес основного зеркала в директиве Host. В данный момент это не прописано. Необходимо добавить в файл robots.txt директиву Host. Директива Host задётся в файле 1 раз, после всех правил.";
                    }
                    $this->fill_task($sheet,$y, WordConstant::HOST_EXIST_TEXT, $status, $color, $state,
                        $textRecommendations, 1);
                    break;
                case 2:
                    $this->fill_task($sheet,$y, WordConstant::ROBOTS_EXIST_TEXT, $status, $color, $state,
                        $textRecommendations, 1);
                    break;
                case 3:
                    $this->fill_task($sheet,$y, WordConstant::ROBOTS_EXIST_TEXT, $status, $color, $state,
                        $textRecommendations, 1);
                    break;
                case 4:
                    $this->fill_task($sheet,$y, WordConstant::ROBOTS_EXIST_TEXT, $status, $color, $state,
                        $textRecommendations, 1);
                    break;
                case 5:
                    $this->fill_task($sheet,$y, WordConstant::ROBOTS_EXIST_TEXT, $status, $color, $state,
                        $textRecommendations, 1);
                    break;
                case 6:
                    $this->fill_task($sheet,$y, WordConstant::ROBOTS_EXIST_TEXT, $status, $color, $state,
                        $textRecommendations, 1);
                    break;
            }
        }
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
            $this->initSheet2($sheet, $data);
            // Выводим содержимое файла
            $objWriter = new PHPExcel_Writer_Excel5($xls);
            if(file_exists('temp//test.xls')) {
                unlink('temp//test.xls');
            }
            $objWriter->save('temp//test.xls');

        } catch (\PHPExcel_Exception $e) {
            echo $e->getMessage().$e->getTraceAsString();
        }
    }
}