<?php

namespace QuizAd\Service\Placements;

use QuizAd\Database\CredentialsRepository;
use QuizAd\Database\PlacementsRepository;
use QuizAd\Database\WebsiteRepository;
use QuizAd\Model\Placements\BadPlacementList;
use QuizAd\Model\Placements\PlacementList;
use QuizAd\Model\Placements\PlacementsFailureResponse;
use QuizAd\Model\Placements\Website;
use QuizAd\Service\OAuth2\OAuth2Service;
use QuizAd\Service\Registration\IpProvider;

class PlacementsService
{
    protected $credentialsRepository;
    protected $placementsRepository;
    protected $websiteRepository;
    protected $placementsApiClient;
    protected $oAuth2Service;
    protected $publisherApi;
    protected $ipService;
    protected $pluginScope;

    /**
     * PlacementsService constructor.
     *
     * @param CredentialsRepository $credentialsRepository
     * @param PlacementsRepository $placementsRepository
     * @param WebsiteRepository $websiteRepository
     * @param PlacementsApiClient $placementsApiClient
     * @param OAuth2Service $oAuth2Service
     * @param IpProvider $ipService
     * @param $pluginScope
     */
    public function __construct(
        CredentialsRepository $credentialsRepository,
        PlacementsRepository  $placementsRepository,
        WebsiteRepository     $websiteRepository,
        PlacementsApiClient   $placementsApiClient,
        OAuth2Service         $oAuth2Service,
        IpProvider            $ipService,
                              $pluginScope
    )
    {
        $this->credentialsRepository = $credentialsRepository;
        $this->placementsRepository  = $placementsRepository;
        $this->websiteRepository     = $websiteRepository;
        $this->placementsApiClient   = $placementsApiClient;
        $this->oAuth2Service         = $oAuth2Service;
        $this->ipService             = $ipService;
        $this->pluginScope           = $pluginScope;
    }

    /**
     * Get placements list - either from database or api.
     * If not in database - try to download them from api and save to db.
     *
     * @return PlacementList
     */
    public function getPlacements()
    {
        // Table must exist - created on plugin bootstrap (Activate plugin)
        $placementsList = $this->placementsRepository->getPlacements();

        if ($placementsList->hasPlacements()) {
            return $placementsList;
        }
        return $this->fetchAllPlacement();
    }

    /**
     * Download all placements from API.
     * @return BadPlacementList|PlacementList
     */
    public function fetchAllPlacement()
    {
        $clientInformation = $this->credentialsRepository->getClientCredentials();

        $this->placementsRepository->removeAllPlacements();

        $serverIp         = $this->ipService->getServerIp();
        $host             = $this->ipService->getHost();
        $apiPublisherData = $this->placementsApiClient->getPlacements($clientInformation, $this->oAuth2Service, $serverIp, $host, $this->pluginScope);
        if ($apiPublisherData instanceof PlacementsFailureResponse) {
            return new PlacementList();
        }

        $applicationId = $this->websiteRepository->getWebsite($clientInformation);
        $this->credentialsRepository->setPublisher($apiPublisherData->getPublisherId(), $clientInformation);

        // we must ignore this result to go on
        // simple reason - only first time we enter this page sets header code
        // any other time - it returns int(0) - because nothing is updated
        $this->websiteRepository->setHeaderCode($apiPublisherData, $applicationId);

        $website = $this->websiteRepository->getWebsite($clientInformation);
        if (!$website) {
            return new BadPlacementList();
        }
        if (count($apiPublisherData->getPlacementList()->getPlacements()) < 1) {
            return new BadPlacementList();
        }
        foreach ($apiPublisherData->getPlacementList()->getPlacements() as $placement) {
            $dbPlacementsResult = $this->placementsRepository->addPlacement($placement, $website);

            if (!$dbPlacementsResult) {
                return new BadPlacementList();
            }
        }

        return $this->placementsRepository->getPlacements();
    }

    /**
     * @return Website
     */
    public function getPlacementProperties()
    {
        $clientInformation = $this->credentialsRepository->getClientCredentials();
        $currentWebsite    = $this->websiteRepository->getWebsite($clientInformation);
        return $this->websiteRepository->getWebsiteWithProperties($currentWebsite);
    }
}