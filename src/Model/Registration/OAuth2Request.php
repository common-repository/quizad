<?php

namespace QuizAd\Model\Registration;

use QuizAd\Model\AbstractValidatableModel;

class OAuth2Request extends AbstractValidatableModel
{
	protected $grant_type;
	protected $scope;

	/**
	 * RegistrationRequest constructor.
	 *
	 * @param $grant_type
	 * @param $scope
	 */
	public function __construct( $grant_type, $scope )
	{
		parent::__construct();
		$this->grant_type = $grant_type;
		$this->scope      = $scope;
	}

	protected function validateFields()
	{
		if ( is_null( $this->grant_type ) || strlen( $this->grant_type ) === 0 ) {
			$this->addErrorMessage( "grant_type was not provided!" );
		}

		if ( is_null( $this->scope ) || strlen( $this->scope ) === 0 ) {
			$this->addErrorMessage( "scope was not provided!" );
		}
	}

	/**
	 * @return string
	 */
	public function getGrantType()
	{
		return $this->grant_type;
	}

	/**
	 * @return string
	 */
	public function getScope()
	{
		return $this->scope;
	}
}