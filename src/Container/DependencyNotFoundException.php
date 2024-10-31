<?php

namespace QuizAd\Container;

use RuntimeException;

/**
 * Class DependencyNotFoundException
 * @package QuizAd\Container
 */
class DependencyNotFoundException extends RuntimeException
{
	/**
	 * DependencyNotFoundException constructor.
	 *
	 * @param string $message
	 */
	public function __construct( $message )
	{
		parent::__construct( $message );
	}


}