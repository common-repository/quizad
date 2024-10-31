<?php


namespace QuizAd\Model\Placements;


class DisplayPosition
{
	protected $getPosition;

	/**
	 * DisplayPosition constructor.
	 *
	 * @param $getPosition
	 */
	public function __construct( $getPosition )
	{
		$this->getPosition = $getPosition;
	}

	/**
	 * @return string
	 */
	public function getGetPosition()
	{
		return esc_attr($this->getPosition);
	}

}