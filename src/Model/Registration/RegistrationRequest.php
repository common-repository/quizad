<?php

namespace QuizAd\Model\Registration;

use QuizAd\Model\AbstractValidatableModel;

class RegistrationRequest extends AbstractValidatableModel
{
	protected $token;
	protected $email;
	protected $host;
	protected $categories;

	/**
	 * RegistrationRequest constructor.
	 *
	 * @param string $token
	 * @param string $email
	 * @param string $host
	 * @param string $password - comma separated int values eg. 1,2,22,31
	 */
	public function __construct($token, $email, $host, $password)
	{
		parent::__construct();
		$this->token      = $token;
		$this->email      = $email;
		$this->host       = $host;
		$this->categories = $password;
	}

	protected function validateFields()
	{
		if (is_null($this->token) || strlen($this->token) === 0)
		{
			$this->addErrorMessage("Token was not provided!");
		}

		if (is_null($this->email) || strlen($this->email) === 0)
		{
			$this->addErrorMessage("Email was not provided!");
		}
		if (empty($this->categories) || !is_string($this->categories) || strlen($this->categories) === 0)
		{
			$this->addErrorMessage("Categories were not provided!");
		}
	}


	/**
	 * @return string
	 */
	public function getToken()
	{
		return esc_attr($this->token);
	}

	/**
	 * @return string
	 */
	public function getEmail()
	{
		return esc_attr($this->email);
	}

	/**
	 * @return string
	 */
	public function getHost()
	{
		return esc_attr($this->host);
	}

	/**
	 * @return array
	 */
	public function getCategories()
	{
		return array_map('intval',explode(',',$this->categories));
	}

}