<?php


namespace QuizAd\Service\Placements;


use QuizAd\Database\CredentialsRepository;
use QuizAd\Database\WebsiteRepository;
use QuizAd\Model\Placements\DisplayPlacementsRequest;
use QuizAd\Model\Placements\DisplayPosition;
use QuizAd\Model\Placements\PlacementUpdatedResponse;
use QuizAd\Model\Placements\PlacementsFailureResponse;
use QuizAd\Model\Placements\Website;

class DisplayPlacementsService
{
    protected $websiteRepository;
    protected $credentialsRepository;

    /**
     * DisplayPlacementsService constructor.
     *
     * @param WebsiteRepository     $websiteRepository
     * @param CredentialsRepository $credentialsRepository
     */
    public function __construct(WebsiteRepository $websiteRepository, CredentialsRepository $credentialsRepository)
    {
        $this->websiteRepository     = $websiteRepository;
        $this->credentialsRepository = $credentialsRepository;
    }

    /**
     * @param DisplayPlacementsRequest $displayPlacementsRequest
     *
     * @return PlacementUpdatedResponse|PlacementsFailureResponse
     */
    public function savePlacementsPositions(DisplayPlacementsRequest $displayPlacementsRequest)
    {
        $displayList       = implode(',', $displayPlacementsRequest->getPlacementsPositions());
        $clientInformation = $this->credentialsRepository->getClientCredentials();
        $applicationId     = $this->websiteRepository->getWebsite($clientInformation);

        $wasUpdated = $this->websiteRepository->setPlacementLocations($applicationId,
                                                                      new DisplayPosition($displayList));

        if ($wasUpdated === 0)
        {
            return new PlacementUpdatedResponse(304, "Placement locations not modified");
        }

        if (!$wasUpdated)
        {
            return new PlacementsFailureResponse(500, "We could not save placement locations");
        }

        return new PlacementUpdatedResponse();
    }

    /**
     * @return Website
     */

    public function getCurrentWebsite()
    {
        if (!$this->websiteRepository->tableExists())
        {
            return new Website();
        }

        return $this->websiteRepository->getWebsiteWithProperties(
            $this->websiteRepository->getWebsite(
                $this->credentialsRepository->getClientCredentials()));
    }

    /**
     * @param DisplayPlacementsRequest $displayPlacementsRequest
     * @return PlacementsFailureResponse|PlacementUpdatedResponse
     */
    public function savePlacementsExcludedPositions(DisplayPlacementsRequest $displayPlacementsRequest)
    {
        $displayList       = implode(',', $displayPlacementsRequest->getPlacementsPositions());
        $clientInformation = $this->credentialsRepository->getClientCredentials();
        $applicationId     = $this->websiteRepository->getWebsite($clientInformation);
        $wasUpdated = $this->websiteRepository->setPlacementExcludedLocations($applicationId,
                                                                              new DisplayPosition($displayList));

        if ($wasUpdated === 0)
        {
            return new PlacementUpdatedResponse(304, "Placement locations not modified");
        }

        if (!$wasUpdated)
        {
            return new PlacementsFailureResponse(500, "We could not save placement locations");
        }

        return new PlacementUpdatedResponse();
    }
}