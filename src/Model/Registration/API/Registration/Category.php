<?php

namespace QuizAd\Model\Registration\API\Registration;

/**
 * Category of the website. Provided by API.
 */
class Category
{
	protected $id;
	protected $name;

	/**
	 * Category constructor.
	 *
	 * @param $id
	 * @param $name
	 */
	public function __construct( $id, $name )
	{
		$this->id   = $id;
		$this->name = $name;
	}

	/**
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return mixed
	 */
	public function getName()
	{
		return $this->name;
	}
}