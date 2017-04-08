<?php

namespace Importer\Reader;

use Importer\Data\Campaign;

class JSONReader implements ReaderInterface
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
     * Gets the data from the specified json file
     * @return \Iterator
     */
    public function read(): \Iterator
    {
        $iterator = new \ArrayIterator;
        
        $this->file->rewind();

        while (!$this->file->eof()) {
            $jsonString = $this->file->fgets();

            $data = json_decode($jsonString, true);
            if (count($data)<5) continue;

            $campaign = new Campaign;
            $campaign->date = $data['date'];
            $campaign->name = $data['name'];
            $campaign->bids = $data['bids'];
            $campaign->impressions = $data['impressions'];
            $campaign->cost = $data['cost'];

            $iterator->append($campaign);
        }
        
        return $iterator;
    }
}