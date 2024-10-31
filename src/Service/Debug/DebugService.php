<?php

namespace QuizAd\Service\Debug;

use QuizAd\Database\CredentialsRepository;
use QuizAd\Database\PlacementsPositionsRepository;
use QuizAd\Database\PlacementsRepository;
use QuizAd\Database\WebsiteRepository;
use QuizAd\Model\Debug\DebugApiRequest;
use QuizAd\Model\Debug\DebugFailureResponse;
use QuizAd\Service\Registration\IpProvider;

class DebugService
{
    protected $credentialsRepository;
    protected $websiteRepository;
    protected $placementsRepository;
    protected $placementPositionRepository;
    protected $ipService;
    protected $pluginScope;
    protected $pluginVersion;
    protected $debugApiClient;

    /**
     * DebugService constructor.
     *
     * @param CredentialsRepository         $credentialsRepository
     * @param WebsiteRepository             $websiteRepository
     * @param PlacementsRepository          $placementsRepository
     * @param PlacementsPositionsRepository $placementsPositionsRepository
     * @param IpProvider                    $ipService
     * @param                               $pluginScope
     * @param                               $pluginVersion
     * @param DebugApiClient                $debugApiClient
     */
    public function __construct(
        CredentialsRepository $credentialsRepository,
        WebsiteRepository $websiteRepository,
        PlacementsRepository $placementsRepository,
        PlacementsPositionsRepository $placementsPositionsRepository,
        IpProvider $ipService,
        $pluginScope,
        $pluginVersion,
        DebugApiClient $debugApiClient
    )
    {
        $this->credentialsRepository       = $credentialsRepository;
        $this->websiteRepository           = $websiteRepository;
        $this->placementsRepository        = $placementsRepository;
        $this->placementPositionRepository = $placementsPositionsRepository;
        $this->ipService                   = $ipService;
        $this->pluginScope                 = $pluginScope;
        $this->pluginVersion               = $pluginVersion;
        $this->debugApiClient              = $debugApiClient;
    }

    /**
     * @param $type
     * @return bool|DebugFailureResponse
     */
    public function debugWordpressApp($type)
    {
        $message        = "This is " . $type . " debug type";
        $serverIp       = $this->ipService->getServerIp();
        $credentials    = $this->credentialsRepository->getClientCredentials();
        $currentWebsite = $this->websiteRepository->getWebsite($credentials);
        $websites       = $this->websiteRepository->getWebsiteWithProperties($currentWebsite);
        $placement      = null;
        if ($credentials->getPublisherId() > 0)
        {
            $placement = $this->placementsRepository->getDefaultPlacement();
        }
        $apiModel = new DebugApiRequest($message, $credentials, $websites, $placement, $serverIp,
                                        $this->pluginScope, $type);

        //TODO: response instanceof DebugFailureResponse
        return $this->debugApiClient->sendDebug($apiModel);
    }
}
