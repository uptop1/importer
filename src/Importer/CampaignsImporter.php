<?php

namespace Importer;

use Importer\Data\Traffic;
use Importer\Writer\WriterInterface;

class CampaignsImporter extends Service
{
    /**
     * Gets the data from the specified reader
     * @return \Iterator
     */
    public function read(): \Iterator
    {
        return $this->reader->read();
    }

    /**
     * Writes traffic data into the specified output
     * @param WriterInterface The writer which stores the data
     * @param Traffic Data to be stored
     * @return bool True when succeeded, False otherwise
     */
    public function write(WriterInterface $writer, Traffic $data): bool{
        return $writer->write($data);
    }
}
