<?php

namespace QuizAd\Service\Placements;

use QuizAd\Database\CredentialsRepository;
use QuizAd\Database\PlacementsRepository;
use QuizAd\Model\Placements\PlacementsFailureResponse;
use QuizAd\Model\Placements\PlacementUpdatedResponse;
use QuizAd\Model\Placements\QuizPlacementsRequest;

class QuizPlacementsService
{
    protected $placementsRepository;
    protected $credentialsRepository;

    /**
     * DisplayPlacementsService constructor.
     *
     * @param PlacementsRepository  $placementsRepository
     * @param CredentialsRepository $credentialsRepository
     */
    public function __construct(
        PlacementsRepository $placementsRepository,
        CredentialsRepository $credentialsRepository
    )
    {
        $this->placementsRepository  = $placementsRepository;
        $this->credentialsRepository = $credentialsRepository;
    }

    /**
     * @param QuizPlacementsRequest $displayPlacementsRequest
     *
     * @return PlacementsFailureResponse|PlacementUpdatedResponse
     */
    public function saveQuizPlacements(QuizPlacementsRequest $displayPlacementsRequest)
    {
        $placement = $this->placementsRepository->getDefaultPlacement();


        $wasUpdated = $this->placementsRepository->setPlacementSentence(
            $placement,
            $displayPlacementsRequest);

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