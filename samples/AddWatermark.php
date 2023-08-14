<?php

require_once('../vendor/autoload.php');

use ComPDFKit\Client\CPDFClient;
use ComPDFKit\Constant\CPDFDocumentEditor;
use ComPDFKit\Exception\CPDFException;

addWatermarkText();

addWatermarkTextImage();

function addWatermarkText(){
    $client = new CPDFClient('public_key', 'secret_key');

    try{
        //Create a task
        $taskInfo = $client->createTask(CPDFDocumentEditor::ADD_WATERMARK);

        //File handling parameter settings
        $file = $client->addFile('test.pdf')
            ->setTextColor('#59c5bb')
            ->setType('text')
            ->setContent('text')
            ->setScale('1')
            ->setOpacity('0.5')
            ->setRotation('0.785')
            ->setTargetPages('1-2')
            ->setVertalign('center')
            ->setHorizalign('left')
            ->setXOffset('100')
            ->setYOffset('100')
            ->setFullScreen('1')
            ->setHorizontalSpace('10')
            ->setVerticalSpace('10');

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
    }catch (CPDFException $e) {
        echo $e->getMessage();
    }
}

function addWatermarkTextImage(){
    $client = new CPDFClient('public_key', 'secret_key');

    try{
        //Create a task
        $taskInfo = $client->createTask(CPDFDocumentEditor::ADD_WATERMARK);

        //File handling parameter settings
        $file = $client->addFile('test.pdf')
            ->setType('image')
            //Set Watermark Image
            ->setImagePath('3.jpg')
            ->setScale('0.5')
            ->setOpacity('0.5')
            ->setRotation('45')
            ->setTargetPages('1-2')
            ->setVertalign('center')
            ->setHorizalign('left')
            ->setXOffset('50')
            ->setYOffset('50')
            ->setFullScreen('1')
            ->setHorizontalSpace('100')
            ->setVerticalSpace('100')
            ->setFront('1');

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
    }catch (CPDFException $e) {
        echo $e->getMessage();
    }
}