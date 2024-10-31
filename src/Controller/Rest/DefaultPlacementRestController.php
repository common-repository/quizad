<?php

namespace QuizAd\Controller\Rest;

use QuizAd\Controller\AbstractRestController;
use QuizAd\Model\Placements\PlacementsRequest;
use QuizAd\Service\Placements\ListPlacementService;
use QuizAd\Service\Registration\RegistrationService;

/**
 * Handles placements themselves.
 */
class DefaultPlacementRestController extends AbstractRestController
{
    protected $listPlacementService;
    protected $registrationService;

    /**
     * PlacementsListController constructor.
     *
     * @param ListPlacementService $listPlacementService
     * @param RegistrationService  $registrationService
     */
    public function __construct(ListPlacementService $listPlacementService, RegistrationService $registrationService)
    {
        $this->listPlacementService = $listPlacementService;
        $this->registrationService  = $registrationService;
    }

    /**
     * @param $request
     *
     * @return array
     */
    protected function handle($request)
    {
        $model = new PlacementsRequest(
            sanitize_text_field($this->getRequestField($request, 'placementId')),
            'correct'
        );

        $validationResult = $model->validate();
        if (!$validationResult->isValid())
        {
            return $validationResult->getErrorMessages();
        }

        $restResponse = $this->listPlacementService->activePlacement($model);

        if (!$restResponse->wasSuccessful())
        {
            return [
                'message' => 'Unknown error',
                'status'  => 500,
                'success' => $restResponse->wasSuccessful()
            ];
        }

        return [
            'message' => 'Placement status changed',
            'status'  => 200,
            'success' => $restResponse->wasSuccessful()
        ];
    }
}