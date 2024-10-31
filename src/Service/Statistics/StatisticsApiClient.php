<?php


namespace QuizAd\Service\Statistics;


use QuizAd\Model\Placements\PlacementsFailureResponse;
use QuizAd\Model\Statistics\StatisticsList;
use QuizAd\Service\AuthorizedApiClient;
use QuizAd\Service\OAuth2\OAuth2Service;
use WP_Error;

class StatisticsApiClient extends AuthorizedApiClient
{
	const APP_REGISTERED = 200;


	public function getStatistics($token, OAuth2Service $OAuth2TokenService)
	{
		$path               = '/api/v1/publishers/statistics';
		$url                = $this->apiHost . $path;
		$authorizedResponse = $this->get($url, $token);

		if ( !$authorizedResponse->isAuthorized())
		{
			$OAuth2TokenService->getCredentials();
			$authorizedResponse = $this->get($url, $token);
		}

		$response = $authorizedResponse->getResponse();

		if ($response instanceof WP_Error)
		{
			return new PlacementsFailureResponse($response->errors['http_request_failed'][0], 500);
		}
		if ( !is_array($response) || $response['response']['code'] !== self::APP_REGISTERED)
		{
			return new PlacementsFailureResponse($response['response']['code'], $response['response']['message']);
		}

		$statisticsArray = json_decode($response['body'], true);

		return new  StatisticsList($statisticsArray);
	}
}