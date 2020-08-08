<?php
/**
 * Created by PhpStorm.
 * User: Miha
 * Date: 05/08/2020
 * Time: 21:15
 */

namespace App\Services;


use App\Interfaces\DownloadManager;

class CURLService implements DownloadManager {
    /** returns content of called url
     * @param string $url
     * @return string
     */
    public function getContent(string $url): string{
        $curl = curl_init(); // Get cURL resource
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
}