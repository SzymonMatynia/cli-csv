<?php


namespace SzymonMatyniaRekrutacjaHRtec\Command;

use SzymonMatyniaRekrutacjaHRtec\Service\CSVManagerServiceInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;

abstract class AbstractFeedCommand extends Command
{
    /**
     * @var CSVManagerServiceInterface
     */
    protected $fm;

    /**
     * @var string
     */
    protected $pathToFile = '/csv/';

    public function __construct(string $name = null, CSVManagerServiceInterface $fm)
    {
        parent::__construct($name);
        $this->fm = $fm;
    }

    protected function configure()
    {
        $this
            ->addArgument('link', InputArgument::REQUIRED, 'Link to look for RSS/Atom')
            ->addArgument('filename', InputArgument::REQUIRED, 'name for the file add / to put in specific file.')
        ;
    }
}
