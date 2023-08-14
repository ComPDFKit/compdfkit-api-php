<?php

namespace ComPDFKit\Resource;

class CPDFTaskResource
{
    public $taskId;

    public function __construct($taskId)
    {
        $this->taskId = $taskId;
    }


}