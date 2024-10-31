<?php


namespace QuizAd\Service\Registration;


use QuizAd\Model\Credentials\Credentials;
use QuizAd\Model\Registration\API\Registration\RegistrationFailureResponse;
use QuizAd\Model\Registration\API\Registration\CategoriesCollection;
use QuizAd\Service\AuthorizedApiClient;
use QuizAd\Service\OAuth2\OAuth2Service;
use WP_Error;

class CategoriesApiClient extends AuthorizedApiClient
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
     * @return RegistrationFailureResponse|CategoriesCollection
     */
    public function categoryApp()
    {
        $path     = '/api/v1/categories';
        $url      = $this->apiHost . $path;
        $response = wp_remote_request($url, [
            'method'  => 'GET',
            'headers' => [
                'Content-Type' => 'application/json'
            ],
        ]);
        if ($response instanceof WP_Error)
        {
            return new RegistrationFailureResponse($response->errors['http_request_failed'][0], 500);
        }
        if (!is_array($response) || $response['response']['code'] !== self::APP_REGISTERED)
        {
            return new RegistrationFailureResponse($response['response']['message'], $response['response']['code']);
        }

        $result = json_decode($response['body'], true);

        return new CategoriesCollection($result);
    }

    public function privateCategoryApp(Credentials $credentials, OAuth2Service $OAuth2TokenService)
    {
        $token              = $credentials->getToken();
        $path               = '/api/v1/login/categories';
        $url                = $this->apiHost . $path;
        $authorizedResponse = $this->get($url, $token);
        if (!$authorizedResponse->isAuthorized())
        {
            $OAuth2TokenService->getCredentials();
            $authorizedResponse = $this->get($url, $token);
        }
        $response = $authorizedResponse->getResponse();

        if ($response instanceof WP_Error)
        {
            return new RegistrationFailureResponse($response->errors['http_request_failed'][0], 500);
        }
        if (!is_array($response) || $response['response']['code'] !== self::APP_REGISTERED)
        {
            return new RegistrationFailureResponse($response['response']['message'], $response['response']['code']);
        }

        $result = json_decode($response['body'], true);

        return new CategoriesCollection($result);
    }
}