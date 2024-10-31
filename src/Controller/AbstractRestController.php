<?php

namespace QuizAd\Controller;

use QuizAd\Service\Security\ArrayEscapingService;
use QuizAd\Service\Wordpress\VersionService;

/**
 * Abstract implementation of REST Controller.
 */
abstract class AbstractRestController
{
	/**
	 * @param $request
	 *
	 * @return array
	 */
	protected abstract function handle($request);

	/**
	 * Calls `handle()` method implemented by specific controller.
	 * Each controller (or controller's model) should validate it's input and sanitize it's output.
	 *
	 * @param $request
	 */
	public function handleRequest($request)
	{
		$response     = $this->handle($request);
		$safeResponse = ArrayEscapingService::recursiveEscape($response);

		$this->sendJson($safeResponse);
	}


	/**
	 * Json response wrapper - in order to support versions >= 4.4.0
	 * with error code.
	 *
	 * @see https://developer.wordpress.org/reference/functions/wp_send_json/
	 *
	 * @param $response
	 * @param null $status_code
	 */
	protected function sendJson($response, $status_code = null)
	{
		if (VersionService::wordpressGreaterThanOrEqual('4.7.0'))
		{
			return wp_send_json($response, $status_code);
		}

		if (null !== $status_code)
		{
			status_header($status_code);
		}

		return wp_send_json($response);
	}


	/**
	 * Get request field value.
	 *
	 * @param array $request
	 * @param string $field
	 * @param null $default
	 *
	 * @return mixed|null
	 */
	protected function getRequestField($request, $field, $default = null)
	{
		return isset($request[ $field ]) ? $request[ $field ] : $default;
	}
}
