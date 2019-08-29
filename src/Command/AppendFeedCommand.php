<?php


namespace App\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AppendFeedCommand extends AbstractFeedCommand
{
    /**
     * @var string
     */
    protected static $defaultName = 'csv:extended';

    private const WRITE_MODE = 'a';

    protected function configure()
    {
        parent::configure();
        $this
            ->setDescription('This command will append or save the csv to file')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        // the feed you want to read and path you want to put file
        $url = $input->getArgument('link');
        $path = $this->pathToFile . $input->getArgument('filename');

        // get array of rss data
        $data = $this->fm->getArrayOfFeedObjects($url);

        // create a file, otherwise throw an error
        $this->fm->writeToCSVFile($path, $data, AppendFeedCommand::WRITE_MODE);

        $io->success('You have your data created.');
    }
}
