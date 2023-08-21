<?php

namespace ComPDFKit\Client;

use ComPDFKit\Constant\CPDFURL;
use ComPDFKit\Exception\CPDFException;
use ComPDFKit\Resource\CPDFFileResource;
use ComPDFKit\Resource\CPDFTaskResource;
use ComPDFKit\Util\CPDFUtil;
use GuzzleHttp\Psr7\Utils;

class CPDFClient
{

    private $publicKey;
    private $secretKey;
    private $accessToken;
    private $expireTime;

    private $httpClient;

    private $fileResources;
    private $taskResource;

    public function __construct($publicKey, $secretKey)
    {
        $this->publicKey = $publicKey;
        $this->secretKey = $secretKey;

        $this->httpClient = new CPDFHttpClient();
    }

    /**
     * @param $accessToken
     * @param $expireIn
     */
    public function setAccessToken($accessToken, $expireIn){
        $this->accessToken = $accessToken;
        $this->expireTime = CPDFUtil::getMillisecond() + $expireIn * 1000 - 5;
    }

    /**
     * @return mixed
     * @throws CPDFException
     */
    public function getAccessToken(){
        $time = CPDFUtil::getMillisecond();

        if(!$this->accessToken || $time > $this->expireTime){
            $this->refreshToken();
        }

        return $this->accessToken;
    }

    /**
     * @throws CPDFException
     */
    public function refreshToken(){
        $tokenInfo = $this->getComPDFKitAuth();
        $this->setAccessToken($tokenInfo['accessToken'], $tokenInfo['expiresIn']);
    }

    /**
     * @return mixed
     * @throws CPDFException
     */
    public function getComPDFKitAuth(){
        $options = ['publicKey' => $this->publicKey, 'secretKey' => $this->secretKey];

        return $this->httpClient->send('POST', CPDFURL::API_V1_OAUTH_TOKEN, [], $options);
    }

    /**
     * @return mixed
     * @throws CPDFException
     */
    public function getSupportTools(){
        return $this->httpClient->send('GET', CPDFURL::API_V1_TOOL_SUPPORT);
    }

    /**
     * @return array
     * @throws CPDFException
     */
    public function getAuthorization(){
        return ['Authorization' => 'Bearer ' . $this->getAccessToken()];
    }

    /**
     * @param $fileKey
     * @param null $language 1:English, 2:Chinese
     * @return mixed
     * @throws CPDFException
     */
    public function getFileInfoByKey($fileKey, $language = null){
        $options = ['fileKey'=>$fileKey];
        if($language){
            $options['language'] = $language;
        }

        return $this->httpClient->send(CPDFURL::HTTP_METHOD_GET, CPDFURL::API_V1_FILE_INFO, $this->getAuthorization(), $options);
    }

    /**
     * @return mixed
     * @throws CPDFException
     */
    public function getAssetInfo(){
        return $this->httpClient->send(CPDFURL::HTTP_METHOD_GET, CPDFURL::API_V1_ASSET_INFO, $this->getAuthorization());
    }

    /**
     * @param string $page
     * @param string $size
     * @return mixed
     * @throws CPDFException
     */
    public function getTaskList($page, $size){
        $options = ['page'=>$page, 'size'=>$size];

        return $this->httpClient->send(CPDFURL::HTTP_METHOD_GET, CPDFURL::API_V1_TASK_LIST, $this->getAuthorization(), $options);
    }

    /**
     * @param $executeType
     * @param int $language 1:English, 2:Chinese
     * @return CPDFClient
     * @throws CPDFException
     */
    public function createTask($executeType, $language = null){
        $url = str_replace('{executeTypeUrl}', $executeType, CPDFURL::API_V1_CREATE_TASK);

        $options = [];

        if($language){
            $options['language'] = $language;
        }

        $result = $this->httpClient->send(CPDFURL::HTTP_METHOD_GET, $url, $this->getAuthorization(), $options);
        $taskId = $result['taskId'];

        $this->taskResource = new CPDFTaskResource($taskId);

        return $result;
    }

    /**
     * @param $filepath
     * @return CPDFFileResource
     */
    public function addFile($filepath){
        $fileSource = new CPDFFileResource($filepath, $this);
        $this->fileResources[] = $fileSource;

        return $fileSource;
    }

    /**
     * @param $taskId
     * @param $filePath
     * @param null $password
     * @param array $params
     * @param int $language
     * @return mixed
     * @throws CPDFException
     */
    public function uploadFile($taskId, $filePath, $password = null, $params = [], $language = null){
        if(!file_exists($filePath)){
            throw new CPDFException('uploadFile', 'File does not exist.');
        }

        $options = [
            'multipart' => [
                [
                    'name' => 'taskId',
                    'contents' => $taskId,
                ],
                [
                    'name' => 'file',
                    'contents' => Utils::tryFopen($filePath, 'r'),
                    'filename' => $filePath,
                    'headers'  => [
                        'Content-Type' => '<Content-type header>'
                    ]
                ]
            ]
        ];

        if(isset($params['type']) && $params['type'] == 'image' && isset($params['imagePath'])){
            $options['multipart'][] = [
                'name' => 'image',
                'contents' => Utils::tryFopen($params['imagePath'], 'r'),
                'filename' => $params['imagePath'],
                'headers'  => [
                    'Content-Type' => '<Content-type header>'
                ]
            ];
            unset($params['imagePath']);
        }

        if($password){
            $options['multipart'][] = ['name' => 'password', 'contents' => $password];
        }

        if(!empty($params)){
            $options['multipart'][] = ['name' => 'parameter', 'contents' => json_encode($params)];
        }

        if($language){
            $options['multipart'][] = ['name' => 'language', 'contents' => $language];
        }

        return $this->httpClient->send(CPDFURL::HTTP_METHOD_POST, CPDFURL::API_V1_UPLOAD_FILE, $this->getAuthorization(), $options);
    }

    /**
     * @param $taskId
     * @param null $language 1:English, 2:Chinese
     * @return CPDFClient
     * @throws CPDFException
     */
    public function executeTask($taskId, $language = null){
        $options = ['taskId' => $taskId];

        if($language){
            $options['language'] = $language;
        }

        $this->httpClient->send(CPDFURL::HTTP_METHOD_GET, CPDFURL::API_V1_EXECUTE_TASK, $this->getAuthorization(), $options);
        return $this;
    }

    /**
     * @param $taskId
     * @param null $language 1:English, 2:Chinese
     * @return mixed
     * @throws CPDFException
     */
    public function getTaskInfo($taskId, $language = null){
        $options = ['taskId' => $taskId];

        if($language){
            $options['language'] = $language;
        }

        return $this->httpClient->send(CPDFURL::HTTP_METHOD_GET, CPDFURL::API_V1_TASK_INFO, $this->getAuthorization(), $options);
    }
}