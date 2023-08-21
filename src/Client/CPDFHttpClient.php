<?php

namespace ComPDFKit\Client;

use ComPDFKit\Constant\CPDFURL;
use ComPDFKit\Exception\CPDFException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Handler\StreamHandler;
use GuzzleHttp\HandlerStack;

class CPDFHttpClient
{

    private $baseUrl = 'https://api-server.compdf.com/server/';

    /**
     * @param $method
     * @param $url
     * @param array $headers
     * @param array $options
     * @return mixed
     * @throws CPDFException
     */
    public function send($method, $url, $headers = [], $options = []){
        $step = $url;
        $handler = new StreamHandler();
        $stack = HandlerStack::create($handler);
        $client = new Client(['handler'=>$stack]);

        $url = $this->baseUrl . $url;

        $body = ['headers' => $headers, 'timeout' => 60];
        if(!empty($options)){
            if(isset($options['multipart'])){
                $body['multipart'] = $options['multipart'];
            }else{
                if($method == CPDFURL::HTTP_METHOD_POST){
                    $body['headers']['Content-Type'] = 'application/json';
                    $body['json'] = $options;
                }elseif($method == CPDFURL::HTTP_METHOD_GET){
                    $body['headers']['Content-Type'] = 'application/json';
                    $body['query'] = $options;
                }
            }
        }

        try{
            $response = $client->request($method, $url, $body);
        }catch (GuzzleException $e){
            $message = 'HTTP Client Error: ' . $e->getMessage();
            throw new CPDFException($step, $message);
        }

        if($response->getStatusCode() != 200){
            $message = 'HTTP Server Error';
            throw new CPDFException($step, $message, $response->getStatusCode());
        }

        $result = json_decode($response->getBody()->getContents(), true);

        if($result['code'] != 200){
            throw new CPDFException($step, $result['msg'], $result['code']);
        }

        return $result['data'];
    }
}