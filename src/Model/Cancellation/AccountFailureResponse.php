<?php
namespace QuizAd\Model\Cancellation;

use QuizAd\Model\Registration\API\FailureApiResponse;
use QuizAd\Model\RestResponseInterface;

class AccountFailureResponse
	extends FailureApiResponse
	implements RestResponseInterface
{
	public function wasSuccessful()
	{
		return false;
	}
}