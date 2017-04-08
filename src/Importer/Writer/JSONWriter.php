<?php

namespace Importer\Writer;

use Importer\Data\Traffic;

class JSONWriter implements WriterInterface
{
    /**
     * @var \SplFileObject
     */
    protected $file;

    /**
     * @param \SplFileObject $file 
     */
    public function __construct(\SplFileObject $file)
    {
        $this->file = $file;
    }
    
    /**
     * Writes traffic data into the specified JSON file
     * @param Traffic Data to be stored
     * @return bool True when succeeded, False otherwise
     */
    public function write(Traffic $traffic): bool
    {
        $supplierId = $traffic->getSupplier()->id;

        foreach($traffic as $campaign){

            // Put supplier ID in the json
            $row = [
                "supplier"=>$supplierId,
            ];

            $row += $campaign->toArray();

            // Put the campaign data as a line in the JSON file
            $length = $this->file->fwrite(json_encode($row) . PHP_EOL);

            if ($length === false){
                return false;
            }
        }

        return true;
    }
}