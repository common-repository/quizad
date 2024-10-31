<?php


namespace QuizAd\Service\Statistics;


use QuizAd\Database\CredentialsRepository;
use QuizAd\Model\Placements\PlacementsFailureResponse;
use QuizAd\Model\Statistics\StatisticsList;
use QuizAd\Service\OAuth2\OAuth2Service;

class StatisticsService
{
	protected $credentialsRepository;
	protected $statisticsApiClient;
	protected $oAuth2Service;

	/**
	 * PlacementsService constructor.
	 *
	 * @param CredentialsRepository $credentialsRepository
	 * @param OAuth2Service $oAuth2Service
	 * @param StatisticsApiClient $statisticsApiClient
	 */
	public function __construct(
		CredentialsRepository $credentialsRepository,
		OAuth2Service $oAuth2Service,
		StatisticsApiClient $statisticsApiClient
	)
	{
		$this->credentialsRepository = $credentialsRepository;
		$this->statisticsApiClient   = $statisticsApiClient;
		$this->oAuth2Service         = $oAuth2Service;
	}

	/**
	 * @return StatisticsList
	 */
	public function getStatistics()
	{
		$clientInformation   = $this->credentialsRepository->getClientCredentials();
		$statisticsApiResult = $this->statisticsApiClient->getStatistics($clientInformation->getToken(), $this->oAuth2Service);
		if ($statisticsApiResult instanceof PlacementsFailureResponse)
		{
			$list = new StatisticsList();
			$list->setWasSuccessful(false);

			return $list;
		}

		return $statisticsApiResult;

	}
}