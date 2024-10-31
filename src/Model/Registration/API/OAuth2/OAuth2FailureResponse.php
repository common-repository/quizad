<?php


namespace QuizAd\Model\Registration\API\OAuth2;


use QuizAd\Model\Registration\API\FailureApiResponse;
use QuizAd\Model\RestResponseInterface;

class OAuth2FailureResponse
	extends FailureApiResponse
	implements RestResponseInterface
{
	public function wasSuccessful()
	{
		return false;
	}
}