<?php
/**
 * Created by PhpStorm.
 * User: nikita
 * Date: 24.06.2018
 * Time: 21:05
 */

namespace App;


class WordConstant
{

    const STATUS_OK = "OK";
    const STATUS_FAIL = "Ошибка";
    const COLOR_OK = '00FF00';
    const COLOR_FAIL = 'FF0000';

    // ROBOTS constants //
    const ROBOTS_EXIST_TEXT = "Проверка наличия файла robot.txt";
    // ROBOTS constants //

    // HOST constants //
    const HOST_EXIST_TEXT = "Проверка указания директивы Host";
    const HOST_ERROR_STATE_TEXT = "'В файле robots.txt не указана директива Host';";
    // HOST constants //
    const TEXT_FAIL_ALL = "Опаньки :) Проверка невозможна, так как директива Host отсутствует :(";

}