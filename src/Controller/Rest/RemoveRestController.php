<?php

namespace QuizAd\Controller\Rest;

use QuizAd\Controller\AbstractRestController;
use QuizAd\Service\Cancellation\AccountService;

/**
 * Handles manage account part.
 */
class RemoveRestController extends AbstractRestController
{
    protected $accountService;

    /**
     * ReinstallRestController constructor.
     * @param AccountService $accountService
     */
    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    /**
     * @param $request
     * @return array
     */
    protected function handle($request)
    {
        $pass         = sanitize_text_field($this->getRequestField($request, 'pwd'));
        $restResponse = $this->accountService->removeWordpressApp($pass);

        if (!$restResponse->wasSuccessful())
        {
            return [
                'message'    => 'Could not remove application. ' . $restResponse->getMessage(),
                'status'     => 500,
                'success'    => $restResponse->wasSuccessful(),
                'delMessage' => $restResponse->getMessage(),
            ];
        }

        deactivate_plugins('/QuizAd/QuizAd.php');
        $restResponse = $this->accountService->reinstallWordpressApp();

        if (!$restResponse->wasSuccessful())
        {
            return [
                'message' => 'Could not remove application. ' . $restResponse->getMessage(),
                'status'  => 500,
                'success' => $restResponse->wasSuccessful()
            ];
        }

        return [
            'message'    => $restResponse->getMessage(),
            'status'     => $restResponse->getCode(),
            'success'    => $restResponse->wasSuccessful(),
            'redirectTo' => admin_url('/plugins.php'),
        ];
    }
}