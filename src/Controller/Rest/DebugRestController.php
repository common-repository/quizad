<?php


namespace QuizAd\Controller\Rest;

use QuizAd\Controller\AbstractRestController;
use QuizAd\Service\Debug\DebugService;

/**
 * Handles debug part.
 */
class DebugRestController extends AbstractRestController
{
    /** @var DebugService */
    protected $debugService;

    /**
     * DebugRestController constructor.
     * @param DebugService $debugService
     */
    public function __construct(DebugService $debugService)
    {
        $this->debugService = $debugService;
    }

    /**
     * @param $request
     * @return array
     */
    protected function handle($request)
    {

        $debugType = sanitize_text_field($this->getRequestField($request, 'type'));
        $isSuccess = $this->debugService->debugWordpressApp($debugType);

        $status = 1;
        if ($isSuccess !== true)
        {
            $status = 0;
        }
        $referer = wp_get_referer();
        if (!$referer)
        {
            $referer = get_home_url();
        }
        wp_redirect($referer . '&d_success=' . $status);
        return [];
    }
}