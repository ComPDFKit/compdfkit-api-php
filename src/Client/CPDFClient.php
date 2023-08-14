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
     * @return mixed
     * @throws CPDFException
     */
    public function getFileInfoByKey($fileKey){
        return $this->httpClient->send(CPDFURL::HTTP_METHOD_GET, CPDFURL::API_V1_FILE_INFO, $this->getAuthorization(), ['fileKey'=>$fileKey]);
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
     * @return CPDFClient
     * @throws CPDFException
     */
    public function createTask($executeType){
        $url = str_replace('{executeTypeUrl}', $executeType, CPDFURL::API_V1_CREATE_TASK);

        $result = $this->httpClient->send(CPDFURL::HTTP_METHOD_GET, $url, $this->getAuthorization());
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
     * @return mixed
     * @throws CPDFException
     */
    public function uploadFile($taskId, $filePath, $password = null, $params = []){
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

        return $this->httpClient->send(CPDFURL::HTTP_METHOD_POST, CPDFURL::API_V1_UPLOAD_FILE, $this->getAuthorization(), $options);
    }

    /**
     * @param $taskId
     * @return CPDFClient
     * @throws CPDFException
     */
    public function executeTask($taskId){
        $this->httpClient->send(CPDFURL::HTTP_METHOD_GET, CPDFURL::API_V1_EXECUTE_TASK, $this->getAuthorization(), ['taskId'=>$taskId]);
        return $this;
    }

    /**
     * @param $taskId
     * @return mixed
     * @throws CPDFException
     */
    public function getTaskInfo($taskId){
        return $this->httpClient->send(CPDFURL::HTTP_METHOD_GET, CPDFURL::API_V1_TASK_INFO, $this->getAuthorization(), ['taskId'=>$taskId]);
    }
}