<?php


namespace QuizAd\Model\Registration\API\OAuth2;


class SuccessfulApiOAuth2
{
	protected $token;
	protected $scope;
	protected $expire_in;


	/**
	 * OAuth2SuccessfulResponse constructor.
	 *
	 * @param $token
	 * @param $scope
	 * @param $expire_in
	 */
	public function __construct( $token, $scope, $expire_in )
	{
		$this->token     = $token;
		$this->scope     = $scope;
		$this->expire_in = $expire_in;
	}

	/**
	 * @return string
	 */
	public function getToken()
	{
		return sanitize_text_field($this->token);
	}

	/**
	 * @return string
	 */
	public function getScope()
	{
		return sanitize_text_field($this->scope);
	}

	/**
	 * @return int
	 */
	public function getExpireIn()
	{
		return intval($this->expire_in);
	}

}