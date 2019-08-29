<?php


namespace App\Service;

interface CSVManagerServiceInterface
{
    /**
     * @param string $path
     * @param array $data
     * @param string $writeMode
     * @return mixed
     */
    public function writeToCSVFile(string $path, array $data, string $writeMode);
}
