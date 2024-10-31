<?php

namespace QuizAd\Model\Placements;

use QuizAd\Model\AbstractValidatableModel;

class PlacementsRequest extends AbstractValidatableModel
{
	protected $placement_id;
	protected $html_code;

	/**
	 * RegistrationRequest constructor.
	 *
	 * @param $placement_id
	 * @param $html_code
	 */
	public function __construct( $placement_id, $html_code )
	{
		parent::__construct();
		$this->placement_id = $placement_id;
		$this->html_code    = $html_code;
	}

	protected function validateFields()
	{
		if ( is_null( $this->placement_id ) || strlen( $this->placement_id ) === 0 ) {
			$this->addErrorMessage( "placement_id was not provided!" );
		}

		if ( is_null( $this->html_code ) || strlen( $this->html_code ) === 0 ) {
			$this->addErrorMessage( "html_code was not provided!" );
		}
	}

	/**
	 * @return string
	 */
	public function getPlacement_id()
	{
		return $this->placement_id;
	}

	/**
	 * @return string
	 */
	public function getHtml_code()
	{
		return $this->html_code;
	}
}