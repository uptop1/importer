<?php

namespace Importer\Writer;

use Importer\Data\Traffic;

class CSVWriter implements WriterInterface
{
    /**
     * @var string
     */
    public $delimiter = ",";

    /**
     * @var string
     */
    public $enclosure = '"';

    /**
     * @var string
     */
    public $escape = "\\";

    /**
     * @var bool
     */
    public $ignoreFirstLine = true;

    /**
     * @var \SplFileObject
     */
    protected $file;

    /**
     * @param \SplFileObject $file 
     * @return type
     */
    public function __construct(\SplFileObject $file)
    {
        $this->file = $file;
    }
    
    /**
     * Writes traffic data into the specified CSV file
     * @param Traffic Data to be stored
     * @return bool True when succeeded, False otherwise
     */
    public function write(Traffic $traffic): bool
    {
        $supplierId = $traffic->getSupplier()->id;

        foreach($traffic as $campaign){

            // Put supplier ID at the first cell
            $row = [
                $supplierId,
            ];

            $row += $campaign->toArray();

            // Put the campaign data as a line in the CSV file
            $length = $this->file->fputcsv($row, $this->delimiter, $this->enclosure, $this->escape);

            if ($length === false){
                return false;
            }
        }

        return true;
    }
}