<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;

class UpdateAllTranslationsCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'translation:update-all';
    protected static $defaultDescription = 'Update all current translation locales with the translations:update --force command.';
    
    private $projectDir;
    private $kernel;

    public function __construct(string $projectDir, KernelInterface $kernel)
    {
        $this->projectDir = $projectDir;
        $this->kernel = $kernel;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("Running translation:update-all");

        $application = new Application($this->kernel);
        $application->setAutoExit(false);
        
        // Get translations directory
        $translationsDir = $this->projectDir . '/translations';

        if (!file_exists($translationsDir)) {
            $output->writeln("ERROR: Translations directory '$translationsDir' does not exist.");
            return Command::FAILURE;
        }

        $directoryIterator = new \RecursiveDirectoryIterator($translationsDir);
        $iteratorIterator = new \RecursiveIteratorIterator($directoryIterator);
        $regex = new \RegexIterator($iteratorIterator, '/^.+\.(.+)+\.xlf$/i', \RecursiveRegexIterator::GET_MATCH);

        // loop through locales and run "translation:update --force" on the locale
        $completed = [];
        foreach ($regex as $arr) {
            $locale = $arr[1];
            if (!in_array($locale, $completed)) {
                $completed[] = $arr[1];
                
                $input = new ArrayInput([
                    'command' => 'translation:update',
                    'locale' => "$locale",
                    '--force' => true,
                ]);

                $output->writeln("Running command 'translation:update' on $locale locale.");

                try {
                    $application->run($input, $output);
                    $output->writeln("Finished running command 'translation:update' on $locale locale.");
                } catch (\Exception $e) {
                    $output->writeln("Error has occurred while running command 'translation:update' on $locale locale.");
                    return Command::FAILURE;
                }
            }
        }
        
        $output->writeln("Completed running command 'translation:update' on all locales.");
        return Command::SUCCESS;
    }
}
