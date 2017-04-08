<?php
namespace Tests\Importer\Reader;

require __DIR__.'/../../../vendor/autoload.php';

use Importer\Reader\CSVReader;
use org\bovigo\vfs\vfsStream;

use PHPUnit\Framework\TestCase;

class CSVReaderTest extends TestCase
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
     * @param string $rowsCount Number of rows in the file
     * @param string $csvContent String contents of CSV file
     *
     * @dataProvider providerCSV
     */
    public function testReadFromCSV($rowsCount, $csvContent)
    {
        $mockFile = vfsStream::newFile("input.csv")->at($this->root)->setContent($csvContent);
        $mockFilePath = vfsStream::url("tmp/input.csv");

        $file = new \SplFileObject($mockFilePath);

        $reader = new CSVReader($file);
        $reader->ignoreFirstLine = true;
        $data = $reader->read();

        $this->assertEquals($rowsCount, $data->count());
    }

    /**
     * Provides test cases for CSV read (testReadFromCSV)
     * @return array
     */
    public function providerCSV()
    {
        return [
            [2, 'Date,Campaign Name,Bids,Impressions,Cost
2016-07-01,398d3b21ac,23568545,12448536,251.78
2016-07-01,281d3b1a8,4879451,1247369,48.69'],
            [0, 'Date,Campaign Name,Bids,Impressions,Cost
'],
            [0, ''],
        ];
    }
}