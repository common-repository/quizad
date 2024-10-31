<?php

namespace QuizAd\Model\View;

class ViewModel
{
	protected $view;
	protected $dataModel;

	/**
	 * ViewModel constructor.
	 *
	 * @param string $view - path to php template
	 * @param mixed $dataModel - arguments to pass in to php template
	 */
	public function __construct( $view, $dataModel )
	{
		$this->view      = $view;
		$this->dataModel = $dataModel;
	}

	/**
	 * Path to view template.
	 *
	 * @return string
	 */
	public function getView()
	{
		return $this->view;
	}

	/**
	 * @return mixed
	 */
	public function getDataModel()
	{
		return $this->dataModel;
	}
}