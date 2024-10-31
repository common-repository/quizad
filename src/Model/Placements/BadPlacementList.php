<?php


namespace QuizAd\Model\Placements;


class BadPlacementList extends PlacementList
{
	public function wasSuccessful()
	{
		return false;
	}
}