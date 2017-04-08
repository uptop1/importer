<?php

namespace Importer\Data;

class Campaign
{
    /**
     * @var string
     */
    public $date;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $bids;

    /**
     * @var string
     */
    public $impressions;

    /**
     * @var string
     */
    public $cost;


    /**
     * Class Constructor
     * @param $data   
     */
    public function __construct(array $data = null)
    {
        if ($data && count($data)>=5){
            $this->date = $data[0];
            $this->name = $data[1];
            $this->bids = $data[2];
            $this->impressions = $data[3];
            $this->cost = $data[4];
        }
    }

    /**
     * Get the array representation
     * @return array
     */
    public function toArray(): array
    {
        return (array)$this;
    }

}