<?php


namespace App\Command;

use App\Repository\FeedRepository;
use App\Service\CSVManagerServiceInterface;
use App\Command\FeedCommandTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AppendFeedCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'csv:extended';

    /**
     *
     */
    private const WRITE_MODE = 'a';

    use FeedCommandTrait;

    public function __construct(string $name = null, FeedRepository $feedRepository, CSVManagerServiceInterface $fm)
    {
        parent::__construct($name);
        $this->feedRepository = $feedRepository;
        $this->fm = $fm;
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('link', InputArgument::REQUIRED, 'Link to look for RSS/Atom')
            ->addArgument('filename', InputArgument::REQUIRED, 'name for the file add / to put in specific directory')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        // the feed you want to read and path you want to put file
        $url = $input->getArgument('link');
        $path = $this->pathToFile . $input->getArgument('filename');

        // get array of rss data
        $data = $this->feedRepository->getArrayOfFeedObjects($url);

        // create a file, otherwise throw an error
        $this->fm->writeToCSVFile($path, $data, 'a');

        $io->success('You have your data created.');
    }
}
