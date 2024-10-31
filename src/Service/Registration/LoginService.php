<?php

namespace QuizAd\Service\Registration;

use QuizAd\Database\CredentialsRepository;
use QuizAd\Database\WebsiteRepository;
use QuizAd\Model\Placements\Website;
use QuizAd\Model\Registration\API\Login\LoginApiRequest;
use QuizAd\Model\Registration\API\Registration\RegistrationFailureResponse;
use QuizAd\Model\Registration\LoginRequest;
use QuizAd\Model\Registration\DB\RegistrationDatabaseFailure;
use QuizAd\Model\Registration\RegistrationSuccessfulResponse;

class LoginService
{
    protected $credentialsRepository;
    protected $websiteRepository;
    protected $credentialsApiClient;
    protected $ipService;
    protected $pluginScope;

    /**
     * RegistrationService constructor.
     *
     * @param CredentialsRepository $credentialsRepository
     * @param WebsiteRepository     $websiteRepository
     * @param CredentialsApiClient  $credentialsApiClient
     * @param IpProvider            $ipService
     * @param                       $pluginScope
     */
    public function __construct(
        CredentialsRepository $credentialsRepository,
        WebsiteRepository $websiteRepository,
        CredentialsApiClient $credentialsApiClient,
        IpProvider $ipService,
        $pluginScope
    )
    {
        $this->credentialsRepository = $credentialsRepository;
        $this->credentialsApiClient  = $credentialsApiClient;
        $this->ipService             = $ipService;
        $this->websiteRepository     = $websiteRepository;
        $this->pluginScope           = $pluginScope;

    }

    /**
     * Register plugin.
     *
     * @param LoginRequest $loginRequest
     *
     * @return RegistrationFailureResponse|RegistrationDatabaseFailure|RegistrationSuccessfulResponse
     */
    public function loginWordpressApp(LoginRequest $loginRequest)
    {
        $serverIp                = $this->ipService->getServerIp();
        $apiModel                = new LoginApiRequest($loginRequest, $serverIp, $this->pluginScope);
        $registrationApiResponse = $this->credentialsApiClient->loginApp($apiModel);

        if ($registrationApiResponse instanceof RegistrationFailureResponse)
        {
            return $registrationApiResponse;
        }
        $dbCredentialsResult = $this->credentialsRepository->addCredentials($registrationApiResponse);

        $clientCredentials = $this->credentialsRepository->getClientCredentials();
        if (!$clientCredentials)
        {
            return new RegistrationDatabaseFailure();
        }
        $website = new Website(
            $registrationApiResponse->getApplicationId(),
            $registrationApiResponse->getEmail(),
            $registrationApiResponse->getCategories(),
            get_current_user_id(),
            get_current_blog_id(),
            null);

        $dbWebsiteResult = $this->websiteRepository->addWebsite($website, $clientCredentials);
        if (!$dbWebsiteResult || !$dbCredentialsResult)
        {
            return new RegistrationDatabaseFailure();
        }

        return new RegistrationSuccessfulResponse();
    }

    /**
     * @param LoginRequest $loginRequest
     * @return array|RegistrationFailureResponse
     */
    public function getCategories(LoginRequest $loginRequest)
    {
        $serverIp = $this->ipService->getServerIp();
        $apiModel = new LoginApiRequest($loginRequest, $serverIp, $this->pluginScope);
        //TODO: response instanceof RegistrationFailureResponse
        return $this->credentialsApiClient->accessTokenApp($apiModel);
    }

}
