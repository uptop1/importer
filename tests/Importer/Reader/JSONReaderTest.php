<?php
namespace Tests\Importer\Reader;

require __DIR__.'/../../../vendor/autoload.php';

use Importer\Reader\JSONReader;
use org\bovigo\vfs\vfsStream;

use PHPUnit\Framework\TestCase;

class JSONReaderTest extends TestCase
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
     * @param string $jsonContent String contents of JSON file
     *
     * @dataProvider providerJSON
     */
    public function testReadFromJSON($rowsCount, $jsonContent)
    {
        $mockFile = vfsStream::newFile("input.json")->at($this->root)->setContent($jsonContent);
        $mockFilePath = vfsStream::url("tmp/input.json");

        $file = new \SplFileObject($mockFilePath);

        $reader = new JSONReader($file);
        $reader->ignoreFirstLine = true;
        $data = $reader->read();

        $this->assertEquals($rowsCount, $data->count());
    }

    /**
     * Provides test cases for JSON read (testReadFromJSON)
     * @return array
     */
    public function providerJSON()
    {
        return [
            [2, '{"supplier":1,"date":"2016-07-01","name":"398d3b21ac","bids":"23568545","impressions":"12448536","cost":"251.78"}
{"supplier":1,"date":"2016-07-01","name":"281d3b1a8","bids":"4879451","impressions":"1247369","cost":"48.69"}'],
            [1, '{"supplier":2,"date":"2016-07-01","name":"kgjo93420","bids":"5345363","impressions":"423526","cost":"63.24"}'],
            [0, ''],
        ];
    }
}