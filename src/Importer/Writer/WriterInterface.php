<?php

namespace Importer\Writer;

use Importer\Data\Traffic;

interface WriterInterface
{
    /**
     * Writes traffic data into the specified output
     * @param Traffic Data to be stored
     * @return bool True when succeeded, False otherwise
     */
    public function write(Traffic $data): bool;
}