<?php


namespace QuizAd\Controller\Rest;

use QuizAd\Controller\AbstractRestController;
use QuizAd\Model\Registration\LoginRequest;
use QuizAd\Service\Registration\LoginService;

/**
 * Handles registration part.
 */
class LoginRestController extends AbstractRestController
{
    /**
     * @var LoginService
     */
    protected $loginService;

    /**
     * LoginRestController constructor.
     * @param LoginService $loginService
     */
    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
    }

    /**
     * @param $request
     * @return array|array[]
     */
    protected function handle($request)
    {
        $model = new LoginRequest(
            sanitize_text_field($this->getRequestField($request, 'token')),
            esc_url_raw($this->getRequestField($request, 'host')),
            sanitize_text_field($this->getRequestField($request, 'username')),
            sanitize_text_field($this->getRequestField($request, 'password'))
        );

        $validationResult = $model->validate();
        if (!$validationResult->isValid())
        {
            return $validationResult->getErrorMessages();
        }

        $restResponse = $this->loginService->loginWordpressApp($model);

        if (!$restResponse->wasSuccessful())
        {
            return [
                'message' => 'Could not register application. ' . $restResponse->getMessage(),
                'status'  => 500,
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