<?php
/**
 * Created by PhpStorm.
 * User: nikita
 * Date: 20.06.2018
 * Time: 11:00
 */
// https://robot-cash.biz/robots.txt - тут нет robots.txt
require_once 'vendor/autoload.php';
$site = $_GET['site'];
$fileInspector = new \App\FileInspector($site);
$fileInspector->startExcel();
