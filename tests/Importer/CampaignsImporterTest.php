<?php
namespace Tests;

require __DIR__.'/../../vendor/autoload.php';

use Importer\Reader\CSVReader;
use Importer\Reader\JSONReader;
use Importer\Writer\CSVWriter;
use Importer\Data\Traffic;
use Importer\Data\Supplier;
use Importer\Data\Campaign;
use Importer\CampaignsImporter;
use org\bovigo\vfs\vfsStream;

use PHPUnit\Framework\TestCase;

class CampaignsImporterTest extends TestCase
{
    private $root;

    public function setUp() {
        $this->root = vfsStream::setup('tmp');
    }

    /**
     * @param string $rowsCount Number of rows in the file
     * @param string $csvContent String contents of CSV file
     * @param string $jsonContent String contents of JSON file
     *
     * @dataProvider providerCSVJSON
     */
    public function testRead($rowsCount, $csvContent, $jsonContent)
    {
        $mockFile = vfsStream::newFile("input.csv")->at($this->root)->setContent($csvContent);
        $mockFilePath = vfsStream::url("tmp/input.csv");

        $file = new \SplFileObject($mockFilePath, "r");

        $reader = new CSVReader($file);
        $campaignsImporter = new CampaignsImporter($reader);
        $data = $campaignsImporter->read();

        $this->assertEquals($rowsCount, $data->count());

        $jsonMockFile = vfsStream::newFile("input.json")->at($this->root)->setContent($jsonContent);
        $jsonMockFilePath = vfsStream::url("tmp/input.json");

        $jsonFile = new \SplFileObject($jsonMockFilePath, "r");

        $jsonReader = new JSONReader($jsonFile);

        $campaignsImporter->setReader($jsonReader);
        $data = $campaignsImporter->read();
        $this->assertEquals($rowsCount, $data->count());
    }

    public function providerCSVJSON()
    {
        return [
            [2,
                'Date,Campaign Name,Bids,Impressions,Cost
2016-07-01,398d3b21ac,23568545,12448536,251.78
2016-07-01,281d3b1a8,4879451,1247369,48.69',
                '{"supplier":1,"date":"2016-07-01","name":"398d3b21ac","bids":"23568545","impressions":"12448536","cost":"251.78"}
{"supplier":1,"date":"2016-07-01","name":"281d3b1a8","bids":"4879451","impressions":"1247369","cost":"48.69"}'],
        ];
    }

    /**
     * @param string $supplierId Supplier number
     * @param string $campaignsData Array contains campagigns to be stored
     * @param string $csvOutput Model CSV output
     *
     * @dataProvider providerTraffic
     */
    public function testWrite($supplierId, $campaignsData, $csvOutput)
    {
        $mockFilePath = vfsStream::url("tmp/output.csv");

        $file = new \SplFileObject($mockFilePath,"w+");

        $supplier = new Supplier($supplierId, "Test Supplier " . $supplierId);

        $campaigns = new \ArrayIterator;
        foreach($campaignsData as $campaignData){
            $campaign = new Campaign($campaignData);
            $campaigns->append($campaign);
        }

        $traffic = new Traffic($supplier, $campaigns);

        $writer = new CSVWriter($file);
        $campaignsImporter = new CampaignsImporter(new CSVReader($file));
        $passed = $campaignsImporter->write($writer, $traffic);

        $this->assertTrue($passed);

        $file->rewind();
        $contents = $file->fread($file->getSize());

        $this->assertEquals($csvOutput, trim($contents));
    }

    public function providerTraffic()
    {
        return [
            [1, [ 
                    ['2016-07-01','398d3b21ac','23568545','12448536','251.78'],
                    ['2016-07-01','281d3b1a8','4879451','1247369','48.69'],
                ],
                '1,2016-07-01,398d3b21ac,23568545,12448536,251.78
1,2016-07-01,281d3b1a8,4879451,1247369,48.69'
            ],
        ];
    }

}