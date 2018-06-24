<?php
/**
 * Created by PhpStorm.
 * User: nikita
 * Date: 20.06.2018
 * Time: 14:09
 */

namespace App;


class FileInspector
{
    private $curl;
    private $data_model;
    private $robot_text;
    private const HTTP_OK = 200;
    public function __construct($site)
    {
        $this->curl = curl_init("$site//robots.txt");
        $this->data_model = new DataModel();
        $this->data_model->site_name = $site;
        $this->data_model->file_exist = $this->file_exist();
        $this->data_model->file_size = $this->file_size($this->robot_text);
        $this->parse($this->robot_text);
    }
    public function file_exist():bool
    {
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($this->curl);
        $http_code = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
        curl_close($this->curl);
        $this->data_model->server_answer_code = $http_code;
        $this->robot_text = $response;
        if($http_code == FileInspector::HTTP_OK) {
            return true;
        }
        return false;
    }
    public function file_size($server_answer): string {
        $fd = fopen("temp//robots.txt","w");
        fwrite($fd, $server_answer);
        fclose($fd);
        return round(filesize("temp//robots.txt") / 1000);
    }
    public function debug($item) {
        echo "<pre>";
        var_dump($item);
        echo "</pre>";
    }
    public function parse($text)
    {
        $regexp = "/host:/i";
        preg_match_all($regexp, $text, $answer_arr_host);
        $regexp = "/sitemap:/i";
        preg_match_all($regexp, $text, $answer_arr_sitemap);
        $this->data_model->host_count = count($answer_arr_host[0]);
        if($this->data_model->host_count == 0) {
            die(WordConstant::TEXT_FAIL_ALL);
        }
        $this->data_model->host_exist = true;
        if(count($answer_arr_sitemap[0]) > 0) {
            $this->data_model->sitemap_exist = true;
        }
    }
    public function createReport()
    {
        $excel_helper = new ExcelHelper();
        $excel_helper->create($this->data_model);
    }
}