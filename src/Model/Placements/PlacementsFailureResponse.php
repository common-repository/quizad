<?php
namespace QuizAd\Model\Placements;

use QuizAd\Model\Registration\API\FailureApiResponse;
use QuizAd\Model\RestResponseInterface;

class PlacementsFailureResponse
	extends FailureApiResponse
	implements RestResponseInterface
{
	public function wasSuccessful()
	{
		return false;
	}
}