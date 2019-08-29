<?php


namespace SzymonMatyniaRekrutacjaHRtec\Service;

interface CSVManagerServiceInterface
{
    /**
     * @param string $url
     * @return mixed
     */
    public function getArrayOfFeedObjects(string $url);

    /**
     * @param string $path
     * @param array $data
     * @param string $writeMode
     * @return mixed
     */
    public function writeToCSVFile(string $path, array $data, string $writeMode);
}
