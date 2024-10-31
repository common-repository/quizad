<?php

namespace QuizAd\Service\Registration;

use QuizAd\Model\Registration\API\Email\ResentEmailApiRequest;
use QuizAd\Model\Registration\API\Login\LoginApiRequest;
use QuizAd\Model\Registration\API\Login\SuccessfulApiLogin;
use QuizAd\Model\Registration\API\Registration\RegistrationApiRequest;
use QuizAd\Model\Registration\API\Registration\RegistrationFailureResponse;
use QuizAd\Model\Registration\API\Registration\SuccessfulApiRegistration;
use QuizAd\Model\RestResponseInterface;
use WP_Error;

class CredentialsApiClient
{
    const APP_SUCCESS = 200;

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
     * @param LoginApiRequest $loginApiRequest
     *
     * @return SuccessfulApiLogin|RegistrationFailureResponse
     */
    public function loginApp(LoginApiRequest $loginApiRequest)
    {
        $path     = '/api/v2/login';
        $url      = $this->apiHost . $path;
        $response = wp_remote_request($url, [
            'method'  => 'POST',
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'body'    => json_encode($loginApiRequest->toArray())
        ]);
        if ($response instanceof WP_Error) {
            return new RegistrationFailureResponse($response->errors['http_request_failed'][0], 500);
        }
        if (!is_array($response) || $response['response']['code'] !== self::APP_SUCCESS) {
            return new RegistrationFailureResponse($response['response']['message'], $response['response']['code']);
        }

        $result = json_decode($response['body'], true);
        $token  = $result['token'];

        $path     = '/api/v2/placements';
        $url      = $this->apiHost . $path;
        $response = wp_remote_request($url, [
            'method'  => 'POST',
            'headers' => [
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ],
            'body'    => json_encode([
                'requestIp'  => $loginApiRequest->toArray()['requestIp'],
                'websiteUrl' => $loginApiRequest->toArray()['websiteUrl'],
                'scope'      => $loginApiRequest->toArray()['scope']
            ])
        ]);
        if ($response instanceof WP_Error) {
            return new RegistrationFailureResponse($response->errors['http_request_failed'][0], 500);
        }
        if (!is_array($response) || $response['response']['code'] !== self::APP_SUCCESS) {
            return new RegistrationFailureResponse($response['response']['message'], $response['response']['code']);
        }

        $result     = json_decode($response['body'], true);
        $placements = $result['placements'];
        if (count($placements) === 0)
            return new RegistrationFailureResponse('No placements found', 500);
        if (!$placements[0]['name'])
            return new RegistrationFailureResponse('Placement is not active', 500);
        if (!$placements[0]['categories'])
            return new RegistrationFailureResponse('No categories found', 500);

        return new SuccessfulApiLogin($loginApiRequest->toArray()['username'],
            $token,
            $placements[0]['name'],
            $token,
            $loginApiRequest->toArray()['username'],
            (array)$placements[0]['categories']);
    }

    /**
     * @param LoginApiRequest $loginApiRequest
     * @return array|RegistrationFailureResponse
     */
    public function accessTokenApp(LoginApiRequest $loginApiRequest)
    {
        $path     = '/api/v1/login/access-token';
        $url      = $this->apiHost . $path;
        $url      .= '?' . http_build_query($loginApiRequest->toArray());
        $response = wp_remote_request($url, [
            'method'  => 'GET',
            'headers' => [
                'Content-Type' => 'application/json'
            ],
        ]);
        if ($response instanceof WP_Error) {
            return new RegistrationFailureResponse($response->errors['http_request_failed'][0], 500);
        }
        if (!is_array($response) || $response['response']['code'] !== self::APP_SUCCESS) {
            return new RegistrationFailureResponse($response['response']['message'], $response['response']['code']);
        }

        $result = json_decode($response['body'], true);
        return (array)$result;
    }

    /**
     * @param ResentEmailApiRequest $emailApiRequest
     * @return RegistrationFailureResponse|SuccessfulApiRegistration
     */
    public function emailResentApp(ResentEmailApiRequest $emailApiRequest)
    {
        $path     = '/api/v1/registrations/mail/resent';
        $url      = $this->apiHost . $path;
        $response = wp_remote_request($url, [
            'method'  => 'POST',
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'body'    => json_encode($emailApiRequest->toArray())
        ]);
        if ($response instanceof WP_Error) {
            return new RegistrationFailureResponse($response->errors['http_request_failed'][0], 500);
        }
        $result = json_decode($response['body'], true);
        if (!is_array($response) || $response['response']['code'] !== self::APP_REGISTERED) {
            return new RegistrationFailureResponse($result['message'], $response['response']['code']);
        }

        return new SuccessfulApiRegistration($result['clientId'], $result['clientSecret'], $result['applicationId'],
            '');
    }
}