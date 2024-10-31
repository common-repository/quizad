<?php


namespace QuizAd\Model\View;


use QuizAd\Model\Placements\PlacementList;
use QuizAd\Model\Placements\Website;

class PlacementsView
{
	protected $statistics;
	protected $placements;
	protected $website;

	public function __construct()
	{
		$this->website    = new Website();
		$this->statistics = null;
		$this->placements = new PlacementList();
	}

	/**
	 * @return PlacementList
	 */
	public function getPlacements()
	{
		return $this->placements;
	}

	/**
	 * @param mixed $placements
	 */
	public function setPlacements(PlacementList $placements)
	{
		$this->placements = $placements;
	}

	/**
	 * @return Website
	 */
	public function getPlacementWebsite()
	{
		return $this->website;
	}

	/**
	 * @param Website $website
	 */
	public function setWebsite($website)
	{
		$this->website = $website;
	}
}