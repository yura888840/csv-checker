<?php

namespace App\Model;

class CSVRow implements Model
{
    private $headers;

    private $row;

    public function __construct(array $headers, array $row)
    {
        $this->headers = $headers;
        $this->row = $row;
    }

    /**
     * @return mixed
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @return array
     */
    public function getRow(): array
    {
        return $this->row;
    }
}
