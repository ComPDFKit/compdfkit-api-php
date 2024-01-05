# ComPDFKit API in PHP

## Introduction

[ComPDFKit](https://www.compdf.com/) offers powerful and steady PDF libraries and complete PDF functions to build PDF viewer and editor, which allows to preview, edit, annotate, sign, encrypt and decrypt PDF files.

[ComPDFKit API](https://api.compdf.com/api-reference/overview) provides a variety of PHP API tools that allow you to create an efficient document processing workflow in a single API call. 

ComPDFKit API allows you to get 1000 files processing monthly now! Just [sign up](https://api.compdf.com/signup) for a free trial and enjoy comprehensive PDF functions.

### Related

- ComPDFKit API - Java Library: [ComPDFKit API - Java Library](https://github.com/ComPDFKit/compdfkit-api-java)
- ComPDFKit API - Swift Library: [ComPDFKit API - Swift Library](https://github.com/ComPDFKit/compdfkit-api-swift)
- ComPDFKit API - Python Library: [ComPDFKit API - Python Library](https://github.com/ComPDFKit/compdfkit-api-python)
- ComPDFKit API - C#.NET Library: [ComPDFKit API - C#.NET Library](https://github.com/ComPDFKit/compdfkit-api-.net)

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

## Usage

### Create An API Client

First of all, please create an API client to complete the authentication. You need to [sign in](https://api.compdf.com/login) your ComPDFKit API account to get your **publicKey** and **secretKey** at the [dashboard](https://api-dashboard.compdf.com/api/keys). If you are new to ComPDFKit, click here to [sign up](https://api.compdf.com/signup) for a free trial to process 1,000 documents per month for free.

- Project public Key: You can find the public key in the **API Keys** section of your ComPDFKit API account.

- Project secret Key: You can find the secret Key in the **API Keys** section of your ComPDFKit API account.

```php
$client = new CPDFClient('public_key', 'secret_key');
```

### Create A Task

A task ID is automatically generated for you based on the type of PDF tool you choose. You can provide the callback notification URL. After the task processing is completed, we will notify you of the task result through the callback interface. You can perform other operations according to the request result, such as checking the status of the task, uploading files, starting the task, or downloading the result file.

```php
// Create a client
$client = new CPDFClient('public_key', 'secret_key');

// Create a task
// Create an example of a PDF TO WORD task
$taskInfo = $client->createTask(CPDFConversion::PDF_TO_WORD);
```

### Upload Files

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

### Execute the Task

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

### Get The Task Info

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

## Examples

There are many examples in the **samples** folder, which show the main features of the ComPDFKit API and how to use them, such as watermarking PDFs, converting PDF to Word, Excel, JPG, PNG, etc. You can copy the code to your project and run it directly. To learn more about the ComPDFKit API, please visit our [API Reference](https://api.compdf.com/api-reference/overview).

## Free Trial

[ComPDFKit API](https://api.compdf.com/) is a powerful API that can be used to create an efficient document processing workflow in a single API call.

If you do not have a ComPDFKit API account, you can [sign up for a free trial](https://api.compdf.com/signup) to process 1,000 documents per month for free.

Once you have a ComPDFKit API account, you can obtain your **publicKey** and **secretKey** in the [dashboard](https://api-dashboard.compdf.com/api/keys).

## Support

[ComPDFKit](https://www.compdf.com/) has a professional R&D team that produces comprehensive technical documentation and guides to help developers. Also, you can get an immediate response when reporting your problems to our support team.

For detailed information, please visit our [Guides page](https://api.compdf.com/api/docs/guides).

Stay updated with the latest improvements through our [Changelog](https://www.compdf.com/api/changelog-compdfkit-api).

For technical assistance, please reach out to our [Technical Support](https://www.compdf.com/support).

To get more details and an accurate quote, please contact our [Sales Team](https://www.compdf.com/contact-sales).

## License

* The code is available as open source under the terms of the [Apache-2.0 License](https://opensource.org/license/apache-2-0).