<?php
namespace QuizAd\Service\Wordpress;

/**
 * Class for managing versions (in various quizAd).
 */
class VersionService
{
	/**
	 * Function created to verify if we can include some features into plugin
	 * or should we make a polyfill beforehand.
	 *
	 * @param string $requiredVersion - like '1.2.0'
	 *
	 * @return bool|int
     */
	public static function wordpressGreaterThanOrEqual($requiredVersion)
	{
		return version_compare(get_bloginfo('version'), $requiredVersion, '>=');
	}
}