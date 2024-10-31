<?php


namespace QuizAd\Service\Placements;

use QuizAd\Model\Credentials\Credentials;
use QuizAd\Model\Placements\PlacementsFailureResponse;
use QuizAd\Model\Placements\Publisher;
use QuizAd\Service\AuthorizedApiClient;
use QuizAd\Service\OAuth2\OAuth2Service;
use WP_Error;

class PlacementsApiClient extends AuthorizedApiClient
{
    const APP_REGISTERED = 200;

    /***
     * @param Credentials $credentials
     * @param OAuth2Service $OAuth2TokenService
     *
     * @return PlacementsFailureResponse|Publisher
     */
    public function getPlacements(Credentials $credentials, OAuth2Service $OAuth2TokenService, $requestIp, $websiteUrl, $scope)
    {
        $payload            = [
            'requestIp'  => $requestIp,
            'websiteUrl' => $websiteUrl,
            'scope'      => $scope
        ];
        $token              = $credentials->getClientSecret();
        $path               = '/api/v2/placements';
        $url                = $this->apiHost . $path;
        $authorizedResponse = $this->post($url, $token, $payload);

        if (!$authorizedResponse->isAuthorized()) {
            $OAuth2TokenService->getCredentials();
            $authorizedResponse = $this->post($url, $token, $payload);
        }
        $response = $authorizedResponse->getResponse();
        if ($response instanceof WP_Error) {
            return new PlacementsFailureResponse($response->errors['http_request_failed'][0], 500);
        }
        if (!is_array($response) || $response['response']['code'] !== self::APP_REGISTERED) {
            return new PlacementsFailureResponse($response['response']['code'], $response['response']['message']);
        }

        $publisherArray = json_decode($response['body'], true);
        return new Publisher($publisherArray);
    }
}