<?php


namespace SzymonMatyniaRekrutacjaHRtec\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class OverwriteFeedCommand extends AbstractFeedCommand
{
    /**
     * @var string
     */
    protected static $defaultName = 'csv:simple';

    private const WRITE_MODE = 'w';

    protected function configure()
    {
        parent::configure();
        $this
            ->setDescription('This command will overwrite the csv to file')
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
        $this->fm->writeToCSVFile($path, $data, OverwriteFeedCommand::WRITE_MODE);

        $io->success('You have your data created.');
    }
}
