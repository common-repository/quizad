<?php


namespace QuizAd\Model;


class AuthorizedResponse
{
	private $status;
	private $response;

	/**
	 * AuthorizedResponse constructor.
	 *
	 * @param $status
	 * @param $response
	 */
	public function __construct( $status, $response )
	{
		$this->status   = $status;
		$this->response = $response;
	}

	public function isAuthorized()
	{
		return $this->status !== 401;
	}

	/**
	 * @return mixed
	 */
	public function getStatus()
	{
		return $this->status;
	}

	/**
	 * @return mixed
	 */
	public function getResponse()
	{
		return $this->response;
	}
}