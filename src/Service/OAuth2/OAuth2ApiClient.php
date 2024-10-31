<?php


namespace QuizAd\Service\OAuth2;


use QuizAd\Model\Registration\API\OAuth2\OAuth2FailureResponse;
use QuizAd\Model\Registration\API\OAuth2\SuccessfulApiOAuth2;
use QuizAd\Model\Registration\API\OAuth2\OAuth2ApiRequest;
use WP_Error;

class OAuth2ApiClient
{
    const APP_REGISTERED = 200;

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


    /**
     * @param OAuth2ApiRequest $oAuth2ApiRequest
     * @param                  $client_id
     * @param                  $client_secret
     *
     * @return OAuth2FailureResponse|SuccessfulApiOAuth2
     */
    public function getToken(OAuth2ApiRequest $oAuth2ApiRequest, $client_id, $client_secret)
    {
        $path     = '/api/v1/oauth/token';
        $url      = $this->apiHost . $path;
        $response = wp_remote_request($url, [
            'method'  => 'POST',
            'headers' => [
                'Content-Type'  => 'application/x-www-form-urlencoded',
                'Authorization' => 'Basic ' . base64_encode($client_id . ':' . $client_secret)

            ],
            'body'    => $oAuth2ApiRequest->toUrlEncoded()
        ]);
        if ($response instanceof WP_Error)
        {
            return new OAuth2FailureResponse($response->errors['http_request_failed'][0], 500);
        }
        if (!is_array($response) || $response['response']['code'] !== self::APP_REGISTERED)
        {
            return new OAuth2FailureResponse($response['response']['message'], $response['response']['code']);
        }

        $result = json_decode($response['body'], true);
        return new SuccessfulApiOAuth2($result['access_token'], $result['scope'], $result['expires_in']);
    }
}