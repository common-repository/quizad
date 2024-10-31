<?php


namespace QuizAd\Controller\Rest;

use QuizAd\Controller\AbstractRestController;
use QuizAd\Model\Registration\LoginRequest;
use QuizAd\Service\Registration\LoginService;

/**
 * Handles registration part.
 */
class AccessTokenRestController extends AbstractRestController
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
            sanitize_text_field($this->getRequestField($request, 'accessToken')),
            esc_url_raw($this->getRequestField($request, 'host')),
            'null'
        );

        $validationResult = $model->validate();
        if (!$validationResult->isValid())
        {
            return $validationResult->getErrorMessages();
        }

        $restResponse = $this->loginService->getCategories($model);

        if (!is_array($restResponse))
        {
            return [
                'message' => 'Could not get application categories. ' . $restResponse->getMessage(),
                'status'  => 500,
                'success' => $restResponse->wasSuccessful()
            ];
        }

        return [
            'message'    => 'Ok',
            'status'     => 200,
            'success'    => true,
            'categories' => $restResponse,
        ];
    }
}