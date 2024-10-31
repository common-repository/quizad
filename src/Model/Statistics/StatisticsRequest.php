<?php


namespace QuizAd\Model\Statistics;


use QuizAd\Model\AbstractValidatableModel;

class StatisticsRequest extends AbstractValidatableModel
{
	protected $date;
	protected $views;

	/**
	 * RegistrationRequest constructor.
	 *
	 * @param $date
	 * @param $views
	 */
	public function __construct($date, $views)
	{
		parent::__construct();

		$this->date  = $date;
		$this->views = $views;
	}

	protected function validateFields()
	{
		if (is_null($this->date) || strlen($this->date) === 0)
		{
			$this->addErrorMessage("date was not provided!");
		}

		if (is_null($this->views) || strlen($this->views) === 0)
		{
			$this->addErrorMessage("views was not provided!");
		}
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