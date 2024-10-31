<?php

namespace QuizAd\Service\Cancellation;

use QuizAd\Model\Cancellation\AccountFailureResponse;
use QuizAd\Model\Credentials\Credentials;
use QuizAd\Service\AuthorizedApiClient;
use QuizAd\Service\OAuth2\OAuth2Service;
use WP_Error;

/**
 * Class AccountApiClient
 * @package QuizAd\Service\Cancellation
 */
class AccountApiClient extends AuthorizedApiClient
{

    const APP_CANCELED = 200;

    /**
     * @param Credentials   $credentials
     * @param OAuth2Service $OAuth2TokenService
     * @return bool|AccountFailureResponse
     */
    public function deleteAccount(Credentials $credentials, OAuth2Service $OAuth2TokenService)
    {
        $token              = $credentials->getToken();
        $path               = '/api/v1/accounts';
        $url                = $this->apiHost . $path;
        $authorizedResponse = $this->delete($url, $token);

        if (!$authorizedResponse->isAuthorized())
        {
            $OAuth2TokenService->getCredentials();
            $authorizedResponse = $this->delete($url, $token);
        }
        $response = $authorizedResponse->getResponse();

        if ($response instanceof WP_Error)
        {
            return new AccountFailureResponse($response->errors['http_request_failed'][0], 500);
        }
        if (!is_array($response) || $response['response']['code'] !== self::APP_CANCELED)
        {
            return new AccountFailureResponse($response['response']['code'], $response['response']['message']);
        }

        return true;
    }
}