<?php
/**
 * Email Importer
 * @author Mostafa Ameen <admin@uptop1.com>
 */

declare(strict_types=1);

namespace Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Importer\CampaignsImporter;
use Importer\Reader\CSVReader;
use Importer\Writer\CSVWriter;
use Importer\Data\Traffic;
use Importer\Data\Supplier;

/**
 * This class implements EmailImporter as a console command.
 */
class ImportCommand extends Command
{
    /**
     * Set command name and arguments
     */
    protected function configure()
    {
        $this->setName('import');
        $this->setDescription('Import traffic data from csv and store them');
        $this->addArgument('id', InputArgument::REQUIRED, "Supplier ID");
        $this->addArgument('file', InputArgument::REQUIRED, "Input CSV file path");
    }

    /**
     * Runs on command execution
     * @param InputInterface $input Inputs from CLI
     * @param OutputInterface $output CLI output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $filePath = $input->getArgument('file');
        $supplierId = $input->getArgument('id');

        // Open the input file
        $file = new \SplFileObject($filePath, "r");

        // Read campaigns
        $reader = new CSVReader($file);
        $campaignsImporter = new CampaignsImporter($reader);
        $campaigns = $campaignsImporter->read();


        // Set storage
        $outputFilePath = __DIR__ . '/../../storage/traffic.csv';
        $file = new \SplFileObject($outputFilePath,"a");
        $supplier = new Supplier($supplierId, "Import Supplier " . $supplierId);
        $traffic = new Traffic($supplier, $campaigns);

        // Write into CSV
        $writer = new CSVWriter($file);
        $passed = $campaignsImporter->write($writer, $traffic);

        if ($passed){
            $io->success("Import succeded into " . realpath($outputFilePath));
        }else{
            $io->warning("An error occurred while storing the data!");
        }
    }


}