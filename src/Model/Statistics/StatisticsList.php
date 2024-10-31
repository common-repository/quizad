<?php

namespace QuizAd\Model\Statistics;

use QuizAd\Model\WasSuccessfulInterface;

class StatisticsList implements WasSuccessfulInterface
{
	protected $statistics;
	protected $requestWasSuccessful;

	/**
	 * ApiStatistics constructor.
	 *
	 * @param $statistics
	 */
	public function __construct($statistics = [])
	{
		$list = [];
		foreach ($statistics as $apiStatistic)
		{
			$list [] = new Statistic(
				$apiStatistic['date'],
				$apiStatistic['views']
			);
		}

		$this->statistics = $list;
	}

	/**
	 * @return Statistic[]
	 */
	public function getStatisticsList()
	{
		return $this->statistics;
	}

	/**
	 * @return boolean
	 */
	public function wasSuccessful()
	{
		return $this->requestWasSuccessful;
	}

	public function toArray()
	{
		return array_map(function (Statistic $statistic) {
			return [
				'date'  => $statistic->getDate(),
				'views' => $statistic->getViews(),
			];
		}, $this->statistics);
	}

	public function setWasSuccessful($wasSuccessful)
	{
		$this->requestWasSuccessful = $wasSuccessful;
	}
}