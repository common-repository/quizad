<?php

namespace QuizAd\Service\Security;

use QuizAd\Functional\ArrayFunctions;

/**
 * Escapes values to secure representation.
 */
class ArrayEscapingService
{
	/**
	 * Recursively escape keys and values.
	 * Good assumption - all keys and values are arrays.
	 *
	 * @param array $array
	 *
	 * @return array
	 */
	public static function recursiveEscape($array)
	{
		return ArrayFunctions::reduce($array, function ($agg, $value, $key) {
			$safeKey   = self::escapeStringLike($key);
			$safeValue = self::escapeAnyValue($value);

			$agg[ $safeKey ] = $safeValue;

			return $agg;
		});
	}

	/**
	 * Escape array or non-array value without risk.
	 *
	 * @param array|mixed $maybeArrayValue
	 *
	 * @return array|string
     */
	protected static function escapeAnyValue($maybeArrayValue)
	{
		if ( !is_array($maybeArrayValue))
		{
			return self::escapeStringLike($maybeArrayValue);
		}

		return self::recursiveEscape($maybeArrayValue);
	}

	/**
	 * Separate method. Allows us to change escaping function in all places any time.
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	protected static function escapeStringLike($string)
	{
		return esc_html(addcslashes($string, "\0..\37"));
	}
}