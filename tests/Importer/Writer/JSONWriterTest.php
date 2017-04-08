<?php
namespace Tests\Importer\Writer;

require __DIR__.'/../../../vendor/autoload.php';

use Importer\Writer\JSONWriter;
use Importer\Data\Traffic;
use Importer\Data\Supplier;
use Importer\Data\Campaign;
use org\bovigo\vfs\vfsStream;

use PHPUnit\Framework\TestCase;

class JSONWriterTest extends TestCase
{
    private $root;

    public function setUp() {
        $this->root = vfsStream::setup('tmp');
    }

    /**
     * @param string $supplierId Supplier number
     * @param string $campaignsData Array contains campagigns to be stored
     * @param string $jsonOutput Model JSON output
     *
     * @dataProvider providerTraffic
     */
    public function testWriteTrafficToJSON($supplierId, $campaignsData, $jsonOutput)
    {
        $mockFilePath = vfsStream::url("tmp/output.json");

        $file = new \SplFileObject($mockFilePath,"w+");

        $supplier = new Supplier($supplierId, "Test Supplier " . $supplierId);

        $campaigns = new \ArrayIterator;
        foreach($campaignsData as $campaignData){
            $campaign = new Campaign($campaignData);
            $campaigns->append($campaign);
        }

        $traffic = new Traffic($supplier, $campaigns);

        $writer = new JSONWriter($file);
        $passed = $writer->write($traffic);

        $this->assertTrue($passed);

        $file->rewind();
        $contents = $file->fread($file->getSize());

        $this->assertEquals($jsonOutput, trim($contents));
    }

    public function providerTraffic()
    {
        return [
            [1, [ 
                    ['2016-07-01','398d3b21ac','23568545','12448536','251.78'],
                    ['2016-07-01','281d3b1a8','4879451','1247369','48.69'],
                ],
                '{"supplier":1,"date":"2016-07-01","name":"398d3b21ac","bids":"23568545","impressions":"12448536","cost":"251.78"}
{"supplier":1,"date":"2016-07-01","name":"281d3b1a8","bids":"4879451","impressions":"1247369","cost":"48.69"}'
            ],
            [2, [
                    ['2016-07-01','kgjo93420','5345363','423526','63.24'],
                ],
                '{"supplier":2,"date":"2016-07-01","name":"kgjo93420","bids":"5345363","impressions":"423526","cost":"63.24"}'
            ],
            [1, [
                    ['2016-07-02','mody73kg83','363436','64564','126.55'],
                ],
                '{"supplier":1,"date":"2016-07-02","name":"mody73kg83","bids":"363436","impressions":"64564","cost":"126.55"}'
            ],
        ];
    }

}