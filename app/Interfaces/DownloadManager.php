<?php
/**
 * Created by PhpStorm.
 * User: Miha
 * Date: 05/08/2020
 * Time: 20:21
 */
namespace App\Interfaces;

interface DownloadManager{
    public function getContent(string $url) : string;
}