<?php


namespace QuizAd\Model\Credentials;


use DateTime;

class Credentials
{
	protected $clientId;
	protected $clientSecret;
	protected $token;
	protected $expireIn;
	protected $publisherId;
	protected $id;

	/**
	 * Credentials constructor.
	 *
	 * @param int $id
	 * @param string $clientId
	 * @param string $clientSecret
	 * @param string $token
	 * @param   $expireIn
	 * @param string $publisherId
	 */
	public function __construct($id = null, $clientId = null, $clientSecret = null, $token = null, $expireIn = null, $publisherId = null)
	{
		$this->id           = $id;
		$this->clientId     = $clientId;
		$this->clientSecret = $clientSecret;
		$this->token        = $token;
		$this->expireIn     = $expireIn;
		$this->publisherId  = $publisherId;
	}

	/**
	 * @return bool
	 */
	public function hasValidToken()
	{
		return $this->hasAnyToken() &&
		       $this->validateExpireIn();
	}

	public function hasAnyToken()
	{
		return $this->token !== null &&
		       strlen($this->token) > 1;
	}

	public function hasCredentials()
	{
		return $this->clientId !== null &&
		       $this->clientSecret !== null &&
		       strlen($this->clientId) > 0 &&
		       strlen($this->clientSecret) > 0;
	}

	/**
	 * @return int
	 */
	public function getId()
	{
		return intval($this->id);
	}

	/**
	 * @return string
	 */
	public function getClientId()
	{
		return esc_attr($this->clientId);
	}

	/**
	 * @return string
	 */
	public function getClientSecret()
	{
		return esc_attr($this->clientSecret);
	}

	/**
	 * @return string
	 */
	public function getToken()
	{
		return esc_attr($this->token);
	}

	/**
	 * @param string $token
	 * @param string $expireIn
	 */
	public function setValidToken($token, $expireIn)
	{
		$this->token    = $token;
		$this->expireIn = $expireIn;
	}

	/**
	 * @return string - date format string eg. 1979-04-02 19:43:22
	 */
	public function getExpireIn()
	{
		return esc_attr($this->expireIn);
	}

//data timestamp = date > is now (new date)

	/**
	 * Returns false if expireIn is not valid or expiration date already passed.
	 * Else returns true (meaning it is valid).
	 *
	 * @return bool
	 */
	private function validateExpireIn()
	{
		$isExpireInEmpty = $this->expireIn === null ||
		                   strlen($this->expireIn) <= 0 ||
		                   !is_string($this->expireIn);
		if ($isExpireInEmpty)
		{
			return false;
		}
		$parseDate = DateTime::createFromFormat('Y-m-d H:i:s', $this->expireIn);
		if ( !$parseDate)
		{
			return false;
		}

		return $parseDate->getTimestamp() > time();
	}

	/**
	 * @return null
	 */
	public function getPublisherId()
	{
		return esc_attr($this->publisherId);
	}
}