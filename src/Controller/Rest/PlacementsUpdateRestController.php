<?php

namespace QuizAd\Controller\Rest;

use QuizAd\Controller\AbstractRestController;
use QuizAd\Service\Placements\PlacementsService;

class PlacementsUpdateRestController extends AbstractRestController
{
    protected $placementsService;

    /**
     * PlacementsUpdateRestController constructor.
     * @param PlacementsService $placementsService
     */
    public function __construct(PlacementsService $placementsService)
    {
        $this->placementsService = $placementsService;
    }


    /**
     * @param $request
     *
     * @return array
     */
    protected function handle($request)
    {
        $restResponse = $this->placementsService->fetchAllPlacement();
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