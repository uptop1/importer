<?php

namespace Importer\Reader;

interface ReaderInterface
{
    /**
     * Gets the data from the specified input
     * @return \Iterator
     */
    public function read(): \Iterator;
}