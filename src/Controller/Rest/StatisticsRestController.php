<?php


namespace QuizAd\Controller\Rest;


use QuizAd\Controller\AbstractRestController;
use QuizAd\Service\Statistics\StatisticsService;

/**
 * Handles statistics retrieval.
 */
class StatisticsRestController extends AbstractRestController
{
	protected $statisticsService;

    /**
     * StatisticsRestController constructor.
     *
     * @param StatisticsService $statisticsService
     */
	public function __construct(StatisticsService $statisticsService)
	{
		$this->statisticsService = $statisticsService;
	}


	protected function handle($request)
	{
		$statistics = $this->statisticsService->getStatistics();

		if ($statistics->wasSuccessful())
		{
			return [
				'message' => 'Unknown error',
				'status'  => 500,
				'success' => $statistics->wasSuccessful()
			];
		}

		return [
			'message'        => 'Success',
			'status'         => 200,
			'success'        => $statistics->wasSuccessful(),
			'statisticsList' => $statistics->toArray(),
		];
	}
}