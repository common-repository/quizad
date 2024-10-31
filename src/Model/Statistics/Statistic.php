<?php


namespace QuizAd\Model\Statistics;


class Statistic
{
	protected $date;
	protected $views;

	/**
	 * Category constructor.
	 *
	 * @param $date
	 * @param $views
	 */
	public function __construct( $date, $views )
	{
		$this->date  = $date;
		$this->views = $views;
	}

	/**
	 * @return mixed
	 */
	public function getDate()
	{
		return $this->date;
	}

	/**
	 * @return mixed
	 */
	public function getViews()
	{
		return $this->views;
	}
}