<?php

namespace QuizAd\Controller\Rest;

use QuizAd\Controller\AbstractRestController;
use QuizAd\Service\Cancellation\AccountService;

/**
 * Handles manage account part.
 */
class ReinstallRestController extends AbstractRestController
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
        $restResponse = $this->accountService->reinstallWordpressApp();

        if (!$restResponse->wasSuccessful())
        {
            return [
                'message' => 'Could not reinstall application. ' . $restResponse->getMessage(),
                'status'  => 500,
                'success' => $restResponse->wasSuccessful()
            ];
        }

        wp_redirect(admin_url('/plugins.php'));
        return [
            'message' => $restResponse->getMessage(),
            'status'  => $restResponse->getCode(),
            'success' => $restResponse->wasSuccessful()
        ];
    }
}