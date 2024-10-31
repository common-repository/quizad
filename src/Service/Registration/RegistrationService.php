<?php


namespace QuizAd\Service\Registration;


use QuizAd\Database\CredentialsRepository;
use QuizAd\Database\WebsiteRepository;
use QuizAd\Model\Placements\Website;
use QuizAd\Model\Registration\API\Email\ResentEmailApiRequest;
use QuizAd\Model\Registration\API\Registration\RegistrationApiRequest;
use QuizAd\Model\Registration\API\Registration\RegistrationFailureResponse;
use QuizAd\Model\Registration\DB\RegistrationDatabaseFailure;
use QuizAd\Model\Registration\RegistrationRequest;
use QuizAd\Model\Registration\RegistrationSuccessfulResponse;

class RegistrationService
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
     * @param RegistrationRequest $registrationRequest
     *
     * @return RegistrationFailureResponse|RegistrationDatabaseFailure|RegistrationSuccessfulResponse
     */
    public function registerWordpressApp(RegistrationRequest $registrationRequest)
    {
        $serverIp                = $this->ipService->getServerIp();
        $apiModel                = new RegistrationApiRequest($registrationRequest, $serverIp, $this->pluginScope);
        $registrationApiResponse = $this->credentialsApiClient->registerApp($apiModel);


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
            $registrationRequest->getEmail(),
            $registrationRequest->getCategories(),
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
     * @param $token
     * @param $websiteUrl
     * @return RegistrationDatabaseFailure|RegistrationFailureResponse|RegistrationSuccessfulResponse
     */
    public function resentEmail($token, $websiteUrl)
    {
        $serverIp          = $this->ipService->getServerIp();
        $clientCredentials = $this->credentialsRepository->getClientCredentials();
        if (!$clientCredentials)
        {
            return new RegistrationDatabaseFailure();
        }
        $clientWebsite = $this->websiteRepository->getWebsite($clientCredentials);
        if (!$clientWebsite)
        {
            return new RegistrationDatabaseFailure();
        }
        $apiModel                = new ResentEmailApiRequest($token, $websiteUrl, $clientWebsite, $serverIp,
                                                             $this->pluginScope);
        $registrationApiResponse = $this->credentialsApiClient->emailResentApp($apiModel);
        if ($registrationApiResponse instanceof RegistrationFailureResponse)
        {
            return $registrationApiResponse;
        }
        return new RegistrationSuccessfulResponse();
    }

}
