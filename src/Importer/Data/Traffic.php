<?php

namespace Importer\Data;

class Traffic implements \IteratorAggregate
{
    /**
     * @var Supplier
     */
    protected $supplier;

    /**
     * @var \Iterator
     */
    protected $campaigns;

    /**
     * Class Constructor
     * @param Supplier $supplier 
     * @param \Iterator $campaigns 
     */
    public function __construct(Supplier $supplier, \Iterator $campaigns)
    {
        $this->supplier = $supplier;
        $this->campaigns = $campaigns;
    }

    /**
     * @return Supplier
     */
    public function getSupplier(){
        return $this->supplier;
    }

    /**
     * @return \Iterator
     */
    public function getIterator()
    {
        return $this->campaigns;
    }
}