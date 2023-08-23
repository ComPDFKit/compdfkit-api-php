## ComPDFKit API in PHP

[ComPDFKit](https://api.compdf.com/api/docs/introduction) API provides a variety of PHP API tools that allow you to create an efficient document processing workflow in a single API call. Try our various APIs for free — no credit card required.

In this guide, we’ll go through how you can use PHP to make HTTP requests with ComPDFKit API.



## Requirements

Programming Environment: PHP Version 7.0 and higher.

Dependencies: Composer.



## Installation

You can install the library via Composer. Run the following command.
``` shell script
composer require compdfkit/compdfkit-api-php
```
Alternatively, you can add "compdfkit/compdfkit-api-php": "^1.2.4" to your ***"composer.json"*** file and then run it.
``` shell script 
composer update
```

If you are not using a PHP framework with autoload feature, you need to use the code below to autoload.
```php
require_once('vendor/autoload.php');
```



## Create API Client

You can use your **publicKey** and **secretKey** to complete the authentication. You need to [sign in](https://api.compdf.com/login) your ComPDFKit API account to get your **publicKey** and **secretKey** at the [dashboard](https://api-dashboard.compdf.com/api/keys). If you are new to ComPDFKit, click here to [sign up](https://api.compdf.com/signup) for a free trial.

- Project public Key: You can find the public key in [Management Panel](https://api-dashboard.compdf.com/api/keys).

- Project secret Key: You can find the secret Key in [Management Panel](https://api-dashboard.compdf.com/api/keys).

```php
$client = new CPDFClient('public_key', 'secret_key');
```



## Create Task

A task ID is automatically generated for you based on the type of PDF tool you choose. You can provide the callback notification URL. After the task processing is completed, we will notify you of the task result through the callback interface. You can perform other operations according to the request result, such as checking the status of the task, uploading files, starting the task, or downloading the result file.

```php
// Create a client
$client = new CPDFClient('public_key', 'secret_key');

// Create a task
// Create an example of a PDF TO WORD task
$taskInfo = $client->createTask(CPDFConversion::PDF_TO_WORD);
```



## Upload Files

Upload the original file and bind the file to the task ID. The field parameter is used to pass the JSON string to set the processing parameters for the file. Each file will generate automatically a unique filekey. Please note that a maximum of five files can be uploaded for a task ID and no files can be uploaded for that task after it has started.

```php
// Create a client
$client = new CPDFClient('public_key', 'secret_key');

// Create a task
// Create an example of a PDF TO WORD task
$taskInfo = $client->createTask(CPDFConversion::PDF_TO_WORD);

// Upload files
$file = $client->addFile('test.pdf')->uploadFile($taskInfo['taskId']);
```



## Execute the task

After the file upload is completed, call this interface with the task ID to process the files.

```php
// Create a client
$client = new CPDFClient('public_key', 'secret_key');

// Create a task
// Create an example of a PDF TO WORD task
$taskInfo = $client->createTask(CPDFConversion::PDF_TO_WORD);

// Upload files
$file = $client->addFile('test.pdf')->uploadFile($taskInfo['taskId']);

// execute Task
$client->executeTask($taskInfo['taskId']);
```



## Get Task Info

Request task status and file-related meta data based on the task ID.

```php
// Create a client
$client = new CPDFClient('public_key', 'secret_key');

// Create a task
// Create an example of a PDF TO WORD task
$taskInfo = $client->createTask(CPDFConversion::PDF_TO_WORD);

// Upload files
$file = $client->addFile('test.pdf')->uploadFile($taskInfo['taskId']);

// Execute Task
$client->executeTask($taskInfo['taskId']);

// Query TaskInfo
$taskInfo = $client->getTaskInfo($taskInfo['taskId']);
```



## Samples

See ***"Samples"*** folder in this folder.



## Related Resources

* [ComPDFKit API Libraries](https://api.compdf.com/api-libraries/overview)
* [ComPDFKit API Documentation](https://api.compdf.com/api/docs/introduction)
