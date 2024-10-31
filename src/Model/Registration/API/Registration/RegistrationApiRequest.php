<?php

namespace QuizAd\Model\Registration\API\Registration;

use QuizAd\Model\Registration\RegistrationRequest;

/**
 * Registration request sent to API.
 */
class RegistrationApiRequest
{
    protected $registrationRequest;
    protected $serverIp;
    protected $scope;

    /**
     * RegistrationInvoice constructor.
     *
     * @param RegistrationRequest $registrationRequest
     * @param                     $serverIp
     * @param                     $scope
     */
    public function __construct(RegistrationRequest $registrationRequest, $serverIp, $scope)
    {
        $this->registrationRequest = $registrationRequest;
        $this->serverIp            = $serverIp;
        $this->scope               = $scope;
    }

    public function toArray()
    {
        return [
            'login'             => $this->registrationRequest->getEmail(),
            'websiteUrl'        => $this->registrationRequest->getHost(),
            'requestIp'         => $this->serverIp,
            'token'             => $this->registrationRequest->getToken(),
            'websiteCategories' => $this->registrationRequest->getCategories(),
            'scope'             => $this->scope,
        ];
    }
}