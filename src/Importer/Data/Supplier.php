<?php

namespace Importer\Data;

class Supplier
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;


    /**
     * Class Constructor
     * @param int   $id   
     * @param string   $name   
     */
    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }
}