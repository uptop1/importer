<?php
namespace Tests\Importer\Writer;

require __DIR__.'/../../../vendor/autoload.php';

use Importer\Writer\CSVWriter;
use Importer\Data\Traffic;
use Importer\Data\Supplier;
use Importer\Data\Campaign;
use org\bovigo\vfs\vfsStream;

use PHPUnit\Framework\TestCase;

class CSVWriterTest extends TestCase
{
    /**
     * @var \org\bovigo\vfs\vfsStreamDirectory
     */
    private $root;

    /**
     * Set filesystem mock before test cases
     */
    public function setUp() {
        $this->root = vfsStream::setup('tmp');
    }

    /**
     * @param string $supplierId Supplier number
     * @param string $campaignsData Array contains campagigns to be stored
     * @param string $csvOutput Model CSV output
     *
     * @dataProvider providerTraffic
     */
    public function testWriteTrafficToCSV($supplierId, $campaignsData, $csvOutput)
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
        $passed = $writer->write($traffic);

        $this->assertTrue($passed);

        $file->rewind();
        $contents = $file->fread($file->getSize());

        $this->assertEquals($csvOutput, trim($contents));
    }

    /**
     * Provides test cases for CSV write (testWriteTrafficToCSV)
     * @return array
     */
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
            [2, [
                    ['2016-07-01','kgjo93420','5345363','423526','63.24'],
                ],
                '2,2016-07-01,kgjo93420,5345363,423526,63.24'
            ],
            [1, [
                    ['2016-07-02','mody73kg83','363436','64564','126.55'],
                ],
                '1,2016-07-02,mody73kg83,363436,64564,126.55'
            ],
        ];
    }

}