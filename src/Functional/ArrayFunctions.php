<?php


namespace QuizAd\Functional;

/**
 * Class packing up useful functions that can introduce functional programming interface.
 * It is nicer to play with them, rather than native arrays.
 *
 * With them you can do this:
 * ```PHP
 * $result1 = ArrayFunctions::map([12, 4, 1], function($value, $key) {
 *     return $value * 3;
 * }); // [36,12,3]
 * $result2 = ArrayFunctions::reduce($result1, function ($agg, $value, $key) {
 *     $agg += $value;
 *     return $agg;
 * }, 0); // 51
 * ```
 *
 * Results are immutable (in fact, they are copies or previous values).
 * It does not have much impact on memory, but to verify results you can use
 * `memory_get_usage()` to check current usage in bytes.
 */
class ArrayFunctions
{
	/**
	 * Performs reduce operation. Inspired by JS and Java functional processing.
	 *
	 * e.g.
	 * ```PHP
	 * $myNumbers = [32, 2, 14, 4];
	 * $doubledBiggerThanFour = reduce(
	 *     $myNumbers, // input
	 *     function ($aggregator, $value, $key) { // my processing function
	 *         if ($value > 4) {
	 *             $aggregator []= $value * 2;
	 *         }
	 *
	 *         return $aggregator; // always return aggregator! never forget or you'll get
	 *         // error or strange results
	 *     },
	 *     []
	 * ); // end of reduce() call
	 * ```
	 *
	 * It executes `$callback` on each key and value of `$valueToReduce`, passing
	 * `$aggregator`, array's current value and current key.
	 * Should always return $aggregator, but not always modify it (like in example above).
	 *
	 * First call to `$callback` sets it's argument `$aggregator` to value provided
	 * in `$initialValue` argument. Each next call is passes a result from previous call.
	 *
	 * Example:
	 * ```PHP
	 * reduce( ['a' => 2, 'b' => 3, 'c' => 4 ] , ... , [ 'my' => 'array' ] );
	 *    1. $result1 = $callback($initialValue, 2, 'a'); - where $initialValue is ['my' => 'array']
	 *    2. $result2 = $callback($result1, 3, 'b');
	 *    3. $result3 = $callback($result2, 4, 'c');
	 *
	 *    // 3 iterations done
	 *
	 *    4. return $result3;
	 * ```
	 *
	 * @param array $arrayToReduce
	 * @param callable $callback - function of signature: `function($aggregator, $value, $key) : mixed`
	 * @param mixed $initialValue
	 *
	 * @return mixed - result of all callback executions
	 */
	public static function reduce(array $arrayToReduce, callable $callback, $initialValue = [])
	{
		$aggregator = $initialValue;

		foreach ($arrayToReduce as $key => $value)
		{
			$aggregator = $callback($aggregator, $value, $key);
		}

		return $aggregator;
	}

	/**
	 * It maps values of array from one to an another. It returns new copy of previous array
	 * (shallow copy - objects in previous array and references might still point
	 * to the same value, so please, be careful).
	 *
	 * E.g.
	 * ```PHP
	 * $oddNumbers = [1,3,5,13,15];
	 * $evenNumbers = ArrayFunctions::map($oddNumbers, function ($value) {
	 *     return $value * 2;
	 * });
	 * // $evenNumbers contains array [2,6,10,26,30]
	 * ```
	 *
	 * @param array $arrayToMap
	 * @param callable $callback - function of signature: `function($value, $key) : array`
	 * @return array
	 */
	public static function map(array $arrayToMap, callable $callback)
	{
		$results = [];
		foreach ($arrayToMap as $key => $value)
		{
			$results[$key] = $callback($value, $key);
		}

		return $results;
	}
}