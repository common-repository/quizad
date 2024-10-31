<?php

namespace QuizAd\Service\Debug;

use QuizAd\Model\Debug\DebugApiRequest;
use QuizAd\Model\Debug\DebugFailureResponse;
use QuizAd\Service\AuthorizedApiClient;
use WP_Error;

class DebugApiClient extends AuthorizedApiClient
{
    const APP_CONFIRMED = 200;
    protected $apiHost;

    /**
     * OAuth2ApiClient constructor.
     *
     * @param $apiHost
     */
    public function __construct($apiHost)
    {
        $this->apiHost = $apiHost;
    }

    /***
     * @param DebugApiRequest $apiRequest
     * @return bool|DebugFailureResponse
     */
    public function sendDebug(DebugApiRequest $apiRequest)
    {
        $path = '/api/v1/profilers/log';
        $url  = $this->apiHost . $path;

        $response = wp_remote_request($url, [
            'method'  => 'POST',
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'body'    => $apiRequest->toArray()
        ]);
        if ($response instanceof WP_Error)
        {
            return new DebugFailureResponse($response->errors['http_request_failed'][0], 500);
        }
        if (!is_array($response) || $response['response']['code'] !== self::APP_CONFIRMED)
        {
            return new DebugFailureResponse($response['response']['code'], $response['response']['message']);
        }
        return true;

    }
}