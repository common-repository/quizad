<?php
namespace QuizAd\Model\Debug;

use QuizAd\Model\Registration\API\FailureApiResponse;
use QuizAd\Model\RestResponseInterface;

class DebugFailureResponse
	extends FailureApiResponse
	implements RestResponseInterface
{
	public function wasSuccessful()
	{
		return false;
	}
}