<?php

namespace QuizAd\Controller\Rest;

use QuizAd\Controller\AbstractRestController;
use QuizAd\Service\Registration\RegistrationService;

/**
 * Handles registration part.
 */
class ResentEmailRestController extends AbstractRestController
{
    /** @var RegistrationService */
    protected $registrationService;

    /**
     * ResentEmailRestController constructor.
     * @param RegistrationService $registrationService
     */
    public function __construct(RegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }

    /**
     * @param $request
     * @return array
     */
    protected function handle($request)
    {
        $token = sanitize_text_field($this->getRequestField($request, 'token'));
        $host  = esc_url_raw($this->getRequestField($request, 'host'));


        if (is_null($token) || strlen($token) === 0)
        {
            return [
                'message' => "Token was not provided!",
                'status'  => 500,
                'success' => false
            ];
        }
        $restResponse = $this->registrationService->resentEmail($token, $host);

        if (!$restResponse->wasSuccessful())
        {
            return [
                'message' => 'Could not send email. ' . $restResponse->getMessage(),
                'status'  => $restResponse->getCode(),
                'success' => $restResponse->wasSuccessful()
            ];
        }

        return [
            'message' => $restResponse->getMessage(),
            'status'  => $restResponse->getCode(),
            'success' => $restResponse->wasSuccessful()
        ];
    }
}