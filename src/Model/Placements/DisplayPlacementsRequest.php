<?php


namespace QuizAd\Model\Placements;


use QuizAd\Model\AbstractValidatableModel;

class DisplayPlacementsRequest extends AbstractValidatableModel
{
	protected $placementsPositions;

	/**
	 * DisplayPlacementsRequest constructor.
	 *
	 * @param string $placementsSentence
	 */
	public function __construct($placementsSentence)
	{
		parent::__construct();
		$this->placementsPositions = $placementsSentence;
	}

	protected function validateFields()
	{
		if ( !(is_string($this->placementsPositions)) )
		{
			$this->addErrorMessage("Placements positions were not checked!");
		}
	}

	/**
	 * @return array
	 */
	public function getPlacementsPositions()
	{
		return explode(',', esc_attr($this->placementsPositions));
	}
}