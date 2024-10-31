<?php

namespace QuizAd\Model\Registration\API\Registration;

use QuizAd\Model\Registration\API\FailureApiResponse;
use QuizAd\Model\RestResponseInterface;

/**
 * Useful for handling known API failure responses like 403, 409, 500, etc.
 */
class RegistrationFailureResponse
	extends FailureApiResponse
	implements RestResponseInterface
{
	/**
	 * RegistrationFailureResponse constructor.
	 *
	 * @param string  $message
	 * @param integer $httpStatusCode
	 */
	public function __construct( $message, $httpStatusCode )
	{
		parent::__construct($httpStatusCode, $message);
	}

	/**
	 * @return false
     */
	public function wasSuccessful()
	{
		return false;
	}
}