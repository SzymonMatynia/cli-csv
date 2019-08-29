<?php


namespace App\Command;

use App\Repository\FeedRepository;
use App\Service\CSVManagerServiceInterface;

trait FeedCommandTrait
{
    /**
     * @var FeedRepository
     */
    private $feedRepository;
    /**
     * @var CSVManagerServiceInterface
     */
    private $fm;

    /**
     * @var string
     */
    private $pathToFile = '/var/csv/';
}
