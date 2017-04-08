<?php

namespace Importer\Reader;

use Importer\Data\Campaign;

class CSVReader implements ReaderInterface
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
     */
    public function __construct(\SplFileObject $file)
    {
        $this->file = $file;
    }

    /**
     * Gets the data from the specified CSV file
     * @return \Iterator
     */
    public function read(): \Iterator
    {
        $iterator = new \ArrayIterator;
        
        $firstLine = true;
        $this->file->rewind();

        while (!$this->file->eof()) {
            $data = $this->file->fgetcsv($this->delimiter, $this->enclosure, $this->escape);

            if ($firstLine && $this->ignoreFirstLine){
                $firstLine = false;
                continue;
            }

            if (count($data)<5) continue;

            $campaign = new Campaign;
            $campaign->date = $data[0];
            $campaign->name = $data[1];
            $campaign->bids = $data[2];
            $campaign->impressions = $data[3];
            $campaign->cost = $data[4];

            $iterator->append($campaign);
        }

        return $iterator;
    }
}