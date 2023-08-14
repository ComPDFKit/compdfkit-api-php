<?php

namespace ComPDFKit\Exception;

use Exception;
use Throwable;

class CPDFException extends Exception
{

    public $step;

    public function __construct($step, $message = "", $code = 0, Throwable $previous = null)
    {
        $this->step = $step;
        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        return "$this->code# step:$this->step; message:$this->message";
    }
}