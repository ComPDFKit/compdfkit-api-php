<?php

require_once('../vendor/autoload.php');

use ComPDFKit\Client\CPDFClient;
use ComPDFKit\Constant\CPDFConversion;
use ComPDFKit\Exception\CPDFException;

$client = new CPDFClient('public_key', 'secret_key');

try{
    //Create a task
    $taskInfo = $client->createTask(CPDFConversion::PDF_TO_JPG);

    // File handling parameter settings
    $file = $client->addFile('test.pdf')
        ->setImgDpi('300');

    //Upload files
    $fileInfo = $file->uploadFile($taskInfo['taskId']);

    //execute Task
    $client->executeTask($taskInfo['taskId']);

    //query TaskInfo
    $taskInfo = $client->getTaskInfo($taskInfo['taskId']);

    while ($taskInfo['taskStatus'] != 'TaskFinish'){
        sleep(5);
        $taskInfo = $client->getTaskInfo($taskInfo['taskId']);
    }

    print_r($taskInfo);die;
}catch (CPDFException $e){
    echo $e->getMessage();
}