<?php

namespace QuizAd\Model\Registration\API\Login;

use QuizAd\Model\Registration\API\SuccessfulApiInterface;

class SuccessfulApiLogin implements SuccessfulApiInterface
{
    protected $clientId;
    protected $clientSecret;
    protected $applicationId;
    protected $token;
    protected $email;
    protected $categories;

    /**
     * RegistrationSuccessfulResponse constructor.
     *
     * @param $clientId
     * @param $clientSecret
     * @param $applicationId
     * @param $token
     * @param $email
     * @param $categories
     */
    public function __construct($clientId, $clientSecret, $applicationId, $token, $email, $categories)
    {
        $this->clientId      = $clientId;
        $this->clientSecret  = $clientSecret;
        $this->applicationId = $applicationId;
        $this->token         = $token;
        $this->email         = $email;
        $this->categories    = $categories;
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

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return array
     */
    public function getCategories()
    {
        return $this->categories;
    }


}