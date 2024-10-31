<?php

namespace QuizAd\Controller\Rest;

use QuizAd\Controller\AbstractRestController;
use QuizAd\Model\Placements\QuizPlacementsRequest;
use QuizAd\Service\Placements\QuizPlacementsService;

/**
 * Class PlacementQuizRestController.
 * Manage properties for quiz placement type.
 * @package QuizAd\Controller\Rest
 */
class PlacementQuizRestController extends AbstractRestController
{
    protected $quizPlacementService;

    /**
     * DisplayPlacementsController constructor.
     *
     * @param QuizPlacementsService $displayPlacementsService
     */
    public function __construct(QuizPlacementsService $displayPlacementsService)
    {
        $this->quizPlacementService = $displayPlacementsService;
    }

    /**
     * @param $request
     *
     * @return array|false|int
     */
    protected function handle($request)
    {
        $model            = new QuizPlacementsRequest(
            sanitize_text_field($this->getRequestField($request, 'placementSentence'))
        );
        $validationResult = $model->validate();
        if (!$validationResult->isValid())
        {
            return $validationResult->getErrorMessages();
        }

        $restResponse = $this->quizPlacementService->saveQuizPlacements($model);

        return [
            'status'  => $restResponse->getCode(),
            'success' => $restResponse->wasSuccessful(),
            'message' => $restResponse->getMessage()
        ];
    }
}
{

}