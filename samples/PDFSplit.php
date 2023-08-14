<?php

require_once('../vendor/autoload.php');

use ComPDFKit\Client\CPDFClient;
use ComPDFKit\Constant\CPDFDocumentEditor;
use ComPDFKit\Exception\CPDFException;

$client = new CPDFClient('public_key', 'secret_key');

try {
    //Create a task
    $taskInfo = $client->createTask(CPDFDocumentEditor::SPLIT);

    // File handling parameter settings
    $file = $client->addFile('test.pdf')
        ->setPageOptions(['1', '2-3']);

    //Upload files
    $fileInfo = $file->uploadFile($taskInfo['taskId']);

    //execute Task
    $client->executeTask($taskInfo['taskId']);

    //query TaskInfo
    $taskInfo = $client->getTaskInfo($taskInfo['taskId']);

    while ($taskInfo['taskStatus'] != 'TaskFinish') {
        sleep(5);
        $taskInfo = $client->getTaskInfo($taskInfo['taskId']);
    }

    print_r($taskInfo);
} catch (CPDFException $e) {
    echo $e->getMessage();
}