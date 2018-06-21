<?php
/**
 * Created by PhpStorm.
 * User: nikita
 * Date: 21.06.2018
 * Time: 15:27
 */

namespace App;


class DataModel
{
    public $file_exist;
    public $host_count;
    public $host_exist;
    public $server_answer_code;
    public $sitemap_exist;
    public $site_name;
    public $file_size;

    public function __construct()
    {
        $this->sitemap_exist = false;
        $this->host_count = 0;
        $this->host_exist = 0;
        $this->server_answer_code = 0;
        $this->file_size = 0;
    }
}