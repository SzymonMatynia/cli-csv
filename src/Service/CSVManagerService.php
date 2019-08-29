<?php


namespace App\Service;

use App\Repository\FeedRepository;

class CSVManagerService implements CSVManagerServiceInterface
{
    private $feedRepository;

    /**
     * CSVManagerService constructor.
     * @param FeedRepository $feedRepository
     */
    public function __construct(FeedRepository $feedRepository)
    {
        $this->feedRepository = $feedRepository;
    }

    /**
     * @param string $url
     * @return array|mixed
     */
    public function getArrayOfFeedObjects(string $url)
    {
        return $this->feedRepository->getArrayOfFeedObjects($url);
    }

    /**
     * @param string $path
     * @param array $data
     * @param string $writeMode
     * @return mixed|void
     * @throws \Exception
     */
    public function writeToCSVFile(string $path, array $data, string $writeMode)
    {
        $this->feedRepository->writeToCSVFile($path, $data, $writeMode);
    }
}
