<?php

namespace QuizAd\Controller\Rest;

use QuizAd\Controller\AbstractRestController;
use QuizAd\Model\Placements\DisplayPlacementsRequest;
use QuizAd\Service\Placements\DisplayPlacementsService;

class PlacementsExcludeRestController extends AbstractRestController
{
    protected $displayPlacementsService;

    /**
     * PlacementsUpdateRestController constructor.
     * @param DisplayPlacementsService $displayPlacementsService
     */
    public function __construct(DisplayPlacementsService $displayPlacementsService)
    {
        $this->displayPlacementsService = $displayPlacementsService;
    }


    /**
     * @param $request
     *
     * @return array
     */
    protected function handle($request)
    {
        $model            = new DisplayPlacementsRequest(
            sanitize_text_field($this->getRequestField($request, 'placementExclude'))
        );
        $validationResult = $model->validate();
        if (!$validationResult->isValid())
        {
            return $validationResult->getErrorMessages();
        }

        $restResponse = $this->displayPlacementsService->savePlacementsExcludedPositions($model);

        return [
            'status'  => $restResponse->getCode(),
            'success' => $restResponse->wasSuccessful(),
            'message' => $restResponse->getMessage()
        ];
    }
}