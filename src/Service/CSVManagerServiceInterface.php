<?php


namespace App\Service;

interface CSVManagerServiceInterface
{
    /**
     * @param string $path
     * @param string $csvData
     * @param string $writeMode
     * @return mixed
     */
    public function writeToCSVFile(string $path, string $csvData, string $writeMode);

    /**
     * @param array $data
     * @param string $path
     * @return mixed
     */
    public function encodeFeedObjectsToCSVDataInAppendMode(array $data, string $path);

    /**
     * @param array $data
     * @return mixed
     */
    public function encodeFeedObjectsToCSVDataInOverwriteMode(array $data);
}
