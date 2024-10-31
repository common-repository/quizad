<?php

namespace QuizAd\Model\Registration\API\Login;

use QuizAd\Model\Registration\LoginRequest;

/**
 * Registration request sent to API.
 */
class LoginApiRequest
{
    protected $loginRequest;
    protected $serverIp;
    protected $scope;

    /**
     * RegistrationInvoice constructor.
     *
     * @param LoginRequest $loginRequest
     * @param                     $serverIp
     * @param                     $scope
     */
    public function __construct(LoginRequest $loginRequest, $serverIp, $scope)
    {
        $this->loginRequest = $loginRequest;
        $this->serverIp     = $serverIp;
        $this->scope        = $scope;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'requestIp'  => $this->serverIp,
            'username'   => $this->loginRequest->getUsername(),
            'scope'      => $this->scope,
            'websiteUrl' => $this->loginRequest->getHost(),
            'password'   => $this->loginRequest->getPassword(),
        ];
    }
}