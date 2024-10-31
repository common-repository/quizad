<?php


namespace QuizAd\Model\Placements;


use QuizAd\Model\RestResponseInterface;

class PlacementUpdatedResponse implements RestResponseInterface
{
	protected $code = 200;
	protected $message = 'Placements successfully updated';

	/**
	 * PlacementUpdatedResponse constructor.
	 *
	 * @param int $code
	 * @param string $message
	 */
	public function __construct($code = 200, $message = '')
	{
		$this->code    = $code;
		$this->message = empty($message) ? $this->message : $message;
	}


	public function getCode()
	{
		return $this->code;
	}

	public function getMessage()
	{
		return $this->message;
	}

	public function wasSuccessful()
	{
		return true;
	}
}