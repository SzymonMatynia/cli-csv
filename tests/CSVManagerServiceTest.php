<?php

namespace App\Tests;

use App\Kernel;
use App\Service\CSVManagerService;
use App\Service\CSVManagerServiceInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer\Encoder\CsvEncoder;

class CSVManagerServiceTest extends KernelTestCase
{
    private static $CSV;
    private static $FEED_REPOSITORY;

    private static $PATH_TO_FILE = "/tests/csv/test.csv";

    private $header = "title,description,link,guid,pubDate,creator";

    public static function setUpBeforeClass()
    {
        self::$kernel = static::createKernel();
        self::$kernel->boot();

        self::$container = self::$kernel->getContainer();

        self::$CSV = self::$container->get('csv.manager');
        self::$FEED_REPOSITORY = self::$container->get('feed.repo');
    }

    public function testWritingDataInAppendModeWhenFileNotExists()
    {
        $data = self::$FEED_REPOSITORY->getArrayOfFeedObjects('http://feeds.bbci.co.uk/news/world/rss.xml');
        self::$CSV->writeToCSVFile(self::$PATH_TO_FILE, $data, 'a');

        $content = file_get_contents(self::$kernel->getProjectDir() . '/tests/csv/test.csv');
        $index = strpos($content, $this->header);
        $this->assertEquals(0, $index);
    }

    public function testWritingDataInAppendModeWhenFileExists()
    {
        $data = self::$FEED_REPOSITORY->getArrayOfFeedObjects('http://feeds.bbci.co.uk/news/world/rss.xml');
        self::$CSV->writeToCSVFile(self::$PATH_TO_FILE, $data, 'a');


        $this->assertEquals(1, 1);
    }

    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass(); // TODO: Change the autogenerated stub
        // delete to see the file
        unlink(self::$kernel->getProjectDir() . self::$PATH_TO_FILE);
    }

    //is that necessary to test OverwriteMode?
}
