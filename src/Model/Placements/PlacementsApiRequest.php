<?php


namespace QuizAd\Model\Placements;

class PlacementsApiRequest
{
	protected $registrationRequest;
	protected $serverIp;

    /**
     * RegistrationInvoice constructor.
     *
     * @param PlacementsRequest $registrationRequest
     */
	public function __construct( PlacementsRequest $registrationRequest )
	{
		$this->registrationRequest = $registrationRequest;
	}

	public function toUrlEncoded()
	{
		return [
			'placement_id' => $this->registrationRequest->getPlacement_id(),
			'html_code'    => $this->registrationRequest->getHtml_code()
		];
	}
}