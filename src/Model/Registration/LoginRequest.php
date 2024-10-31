<?php

namespace QuizAd\Model\Registration;

use QuizAd\Model\AbstractValidatableModel;

class LoginRequest extends AbstractValidatableModel
{
    protected $token;
    protected $username;
    protected $host;
    protected $password;

    /**
     * RegistrationRequest constructor.
     *
     * @param string $token
     * @param string $username
     * @param        $host
     * @param string $password
     */
    public function __construct($token, $host, $username, $password)
    {
        parent::__construct();
        $this->token    = $token;
        $this->host     = $host;
        $this->username = $username;
        $this->password = $password;
    }

    protected function validateFields()
    {
        if (is_null($this->token) || strlen($this->token) === 0) {
            $this->addErrorMessage("Recaptcha Token was not provided!");
        }
        if (is_null($this->username) || strlen($this->username) === 0) {
            $this->addErrorMessage("Username was not provided!");
        }
        if (is_null($this->password) || strlen($this->password) === 0) {
            $this->addErrorMessage("Password was not provided!");
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
    public function getUsername()
    {
        return esc_attr($this->username);
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
    public function getPassword()
    {
        return esc_attr($this->password);
    }
}