<?php


namespace QuizAd\Controller\Rest;


use QuizAd\Controller\AbstractRestController;
use QuizAd\Model\Placements\DisplayPlacementsRequest;
use QuizAd\Service\Placements\DisplayPlacementsService;

/**
 * Manages specific pages/places on website that are allowed to display ads.
 */
class PlacementLocationsRestController extends AbstractRestController
{
	protected $displayPlacementsService;

	/**
	 * DisplayPlacementsController constructor.
	 *
	 * @param DisplayPlacementsService $displayPlacementsService
	 */
	public function __construct(DisplayPlacementsService $displayPlacementsService)
	{
		$this->displayPlacementsService = $displayPlacementsService;
	}

	/**
	 * @param $request
	 *
	 * @return array|false|int
	 */
	protected function handle($request)
	{
		$model = new DisplayPlacementsRequest(
			sanitize_text_field($this->getRequestField($request, 'placementDisplay'))
		);
		$validationResult = $model->validate();
		if ( !$validationResult->isValid())
		{
			return $validationResult->getErrorMessages();
		}

		$restResponse = $this->displayPlacementsService->savePlacementsPositions($model);

		return [
			'status' => $restResponse->getCode(),
			'success' => $restResponse->wasSuccessful(),
			'message' => $restResponse->getMessage()
		];
	}
}