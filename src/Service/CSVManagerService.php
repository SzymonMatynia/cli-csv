<?php


namespace App\Service;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\EncoderInterface;

class CSVManagerService implements CSVManagerServiceInterface
{
    private const CSV_HEADER = "title,description,link,guid,pubDate,creator";

    /**
     * @var KernelInterface
     */
    private $kernel;
    /**
     * @var EncoderInterface
     */
    private $encoder;

    public function __construct(KernelInterface $kernel, EncoderInterface $encoder)
    {
        $this->kernel = $kernel;
        $this->encoder = $encoder;
    }

    /**
     * @param string $path
     * @param string $csvData
     * @param string $writeMode
     * @throws \Exception
     */
    public function writeToCSVFile(string $path, string $csvData, string $writeMode) : void
    {
        $fp = @fopen($this->kernel->getProjectDir() . '/var/csv/' . $path, $writeMode);
        if (!$fp) {
            throw new \Exception('Directory doesnt exist. Try again');
        }
        fputs($fp, $csvData);
        fclose($fp);
    }

    /**
     * @param array $data
     * @param string $path
     * @return string
     */
    public function encodeFeedObjectsToCSVDataInAppendMode(array $data, string $path) : string
    {
        $data = $this->getDataToEncodeFromFeedObjects($data);

        if (trim(fgets(fopen($this->kernel->getProjectDir() . '/var/csv/' . $path, 'r'))) === CSVManagerService::CSV_HEADER) {
            return $this->encoder->encode($data, 'csv', [CsvEncoder::NO_HEADERS_KEY => true]);
        } else {
            return $this->encoder->encode($data, 'csv');
        }
    }

    /**
     * @param array $data
     * @return string
     */
    public function encodeFeedObjectsToCSVDataInOverwriteMode(array $data) : string
    {
        $data = $this->getDataToEncodeFromFeedObjects($data);
        return $this->encoder->encode($data, 'csv');
    }

    /**
     * @param array $data
     * @return array
     */
    private function getDataToEncodeFromFeedObjects(array $data) : array
    {
        $dataToEncode = [];
        foreach ($data as $feedObj) {
            $dataToEncode[] = $feedObj->getDataToEncode();
        }
        return $dataToEncode;
    }
}
