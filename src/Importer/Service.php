<?php

namespace Importer;

use Importer\Data\Traffic;
use Importer\Reader\ReaderInterface;
use Importer\Writer\WriterInterface;

abstract class Service
{
    /**
     * @var ReaderInterface
     */
    protected $reader;

    /**
     * @param ReaderInterface $reader
     */
    public function __construct(ReaderInterface $reader)
    {
        $this->reader = $reader;
    }

    /**
     * @param ReaderInterface $reader
     */
    public function setReader(ReaderInterface $reader)
    {
        $this->reader = $reader;
    }

    /**
     * Gets the data from the specified reader
     * @return \Iterator
     */
    abstract public function read(): \Iterator;

    /**
     * Writes traffic data into the specified output
     * @param WriterInterface The writer which stores the data
     * @param Traffic Data to be stored
     * @return bool True when succeeded, False otherwise
     */
    abstract public function write(WriterInterface $writer, Traffic $data): bool;
}