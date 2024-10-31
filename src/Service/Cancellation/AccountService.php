<?php

namespace QuizAd\Service\Cancellation;

use QuizAd\Database\CredentialsRepository;
use QuizAd\Database\PlacementsPositionsRepository;
use QuizAd\Database\PlacementsRepository;
use QuizAd\Database\WebsiteRepository;
use QuizAd\Model\Cancellation\AccountPasswordFailure;
use QuizAd\Model\Cancellation\AccountSuccessfulResponse;
use QuizAd\Model\Cancellation\AccountDatabaseFailure;
use QuizAd\Service\OAuth2\OAuth2Service;

/**
 * Class AccountService
 * @package QuizAd\Service\Registration
 */
class AccountService
{
    /**
     * @var CredentialsRepository
     */
    protected $credentialsRepository;
    /**
     * @var WebsiteRepository
     */
    protected $websiteRepository;
    /**
     * @var PlacementsRepository
     */
    protected $placementRepository;
    /**
     * @var PlacementsPositionsRepository
     */
    protected $placementPositionRepository;
    /**
     * @var OAuth2Service
     */
    protected $oAuth2Service;
    /**
     * @var AccountApiClient
     */
    protected $accountApiClient;

    /**
     * RegistrationService constructor.
     *
     * @param CredentialsRepository         $credentialsRepository
     * @param WebsiteRepository             $websiteRepository
     * @param PlacementsRepository          $placementRepository
     * @param PlacementsPositionsRepository $placementPositionRepository
     * @param OAuth2Service                 $oAuth2Service
     * @param AccountApiClient              $accountApiClient
     */
    public function __construct(
        CredentialsRepository $credentialsRepository,
        WebsiteRepository $websiteRepository,
        PlacementsRepository $placementRepository,
        PlacementsPositionsRepository $placementPositionRepository,
        OAuth2Service $oAuth2Service,
        AccountApiClient $accountApiClient
    )
    {
        $this->credentialsRepository       = $credentialsRepository;
        $this->websiteRepository           = $websiteRepository;
        $this->placementRepository         = $placementRepository;
        $this->placementPositionRepository = $placementPositionRepository;
        $this->oAuth2Service               = $oAuth2Service;
        $this->accountApiClient            = $accountApiClient;

    }

    /**
     * Register plugin.
     *
     * @return AccountSuccessfulResponse|AccountDatabaseFailure
     */
    public function reinstallWordpressApp()
    {
        $dropCredentials       = $this->credentialsRepository->dropTable();
        $dropWebsite           = $this->websiteRepository->dropTable();
        $dropPlacements        = $this->placementRepository->dropTable();
        $dropPlacementPosition = $this->placementPositionRepository->dropTable();

        if (!$dropCredentials || !$dropWebsite || !$dropPlacements || !$dropPlacementPosition)
        {
            return new AccountDatabaseFailure();
        }

        return new AccountSuccessfulResponse();
    }

    /**
     * @param $pass
     * @return AccountPasswordFailure|AccountSuccessfulResponse|AccountDatabaseFailure
     */
    public function removeWordpressApp($pass)
    {
        $user = get_user_by('ID', get_current_user_id());
        if (!$user || !wp_check_password($pass, $user->data->user_pass, $user->ID))
        {
            return new AccountPasswordFailure();
        }
        $clientInformation = $this->credentialsRepository->getClientCredentials();

        $removeRequest = $this->accountApiClient->deleteAccount($clientInformation, $this->oAuth2Service);
        if (!$removeRequest)
        {
            return new AccountDatabaseFailure();
        }

        return new AccountSuccessfulResponse();
    }

}
