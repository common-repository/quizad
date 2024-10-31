<?php

namespace QuizAd\Model\Registration\API\Email;

use QuizAd\Model\Placements\Website;
use QuizAd\Model\Registration\RegistrationRequest;

/**
 * Resent Email request sent to API.
 */
class ResentEmailApiRequest
{
    protected $token;
    protected $serverIp;
    protected $scope;
    protected $websiteUrl;
    private   $website;

    /**
     * RegistrationInvoice constructor.
     *
     * @param RegistrationRequest $token
     * @param                     $websiteUrl
     * @param Website             $clientWebsite
     * @param                     $serverIp
     * @param                     $scope
     */
    public function __construct($token, $websiteUrl, Website $clientWebsite, $serverIp, $scope)
    {
        $this->token      = $token;
        $this->websiteUrl = $websiteUrl;
        $this->scope      = $scope;
        $this->website    = $clientWebsite;
        $this->serverIp   = $serverIp;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'login'             => $this->website->getApplicationEmail(),
            'websiteUrl'        => $this->websiteUrl,
            'requestIp'         => $this->serverIp,
            'token'             => $this->token,
            'websiteCategories' => $this->website->getUserCategories(),
            'scope'             => $this->scope,
        ];
    }
}