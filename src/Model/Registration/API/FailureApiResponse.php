<?php

namespace QuizAd\Model\Registration\API;

/**
 * Failure API response - simple class.
 */
class FailureApiResponse
{
	protected $httpStatusCode;
	protected $message;

	/**
	 * FailureApiResponse constructor.
	 *
	 * @param $httpStatusCode
	 * @param $message
	 */
	public function __construct($httpStatusCode, $message = '')
	{
		$this->httpStatusCode = $httpStatusCode;
		$this->message        = $message;
	}

	/**
	 * @return integer
	 */
	public function getCode()
	{
		return $this->httpStatusCode;
	}

	/**
	 * @return string
	 */
	public function getMessage()
	{
		return $this->message;
	}
}