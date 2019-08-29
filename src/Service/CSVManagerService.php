<?php


namespace App\Service;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\EncoderInterface;

class CSVManagerService implements CSVManagerServiceInterface
{

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
     * @param array $data
     * @param string $writeMode
     * @throws \Exception
     */
    public function writeToCSVFile(string $path, array $data, string $writeMode) : void
    {
        $this->path = $this->kernel->getProjectDir() . $path;

        $data = $this->getDataToEncodeFromFeedObjects($data);

        $fp = @fopen($this->path, $writeMode);
        if (!$fp) {
            throw new \Exception('Directory doesnt exist.');
        }

        $dataEncoded = $this->encodeDataToCSV($writeMode, $data);

        fputs($fp, $dataEncoded);
    }

    /**
     * @param string $writeMode
     * @param $data
     * @return bool|float|int|string
     */
    private function encodeDataToCSV(string $writeMode, $data)
    {
        if ($writeMode === 'a' && filesize($this->path) !== 0) {
            return $this->encoder->encode($data, 'csv', [CsvEncoder::NO_HEADERS_KEY => true]);
        }
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
