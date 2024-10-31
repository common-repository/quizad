<?php

namespace QuizAd\Model\Registration\API\Registration;

use QuizAd\Model\Registration\API\SuccessfulApiInterface;

class SuccessfulApiRegistration implements SuccessfulApiInterface
{
    protected $clientId;
    protected $clientSecret;
    protected $applicationId;
    protected $token;

    /**
     * RegistrationSuccessfulResponse constructor.
     *
     * @param $clientId
     * @param $clientSecret
     * @param $applicationId
     * @param $token
     */
    public function __construct($clientId, $clientSecret, $applicationId, $token)
    {
        $this->clientId      = $clientId;
        $this->clientSecret  = $clientSecret;
        $this->applicationId = $applicationId;
        $this->token         = $token;
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        return sanitize_text_field($this->clientId);
    }

    /**
     * @return string
     */
    public function getClientSecret()
    {
        return sanitize_text_field($this->clientSecret);
    }

    /**
     * @return string
     */
    public function getApplicationId()
    {
        return sanitize_text_field($this->applicationId);
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return sanitize_text_field($this->token);
    }
}