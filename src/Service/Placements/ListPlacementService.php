<?php

namespace QuizAd\Service\Placements;

use QuizAd\Database\PlacementsRepository;
use QuizAd\Model\Placements\BadPlacementList;
use QuizAd\Model\Placements\PlacementsRequest;

class ListPlacementService
{
	protected $placementsRepository;
	protected $placementsService;

	/**
	 * DisplayPlacementsService constructor.
	 *
	 * @param PlacementsRepository $placementsRepository
	 * @param PlacementsService $placementsService
	 */
	public function __construct(PlacementsRepository $placementsRepository, PlacementsService $placementsService)
	{
		$this->placementsRepository = $placementsRepository;
		$this->placementsService    = $placementsService;
	}

	public function activePlacement(PlacementsRequest $placementsRequest)
	{
	    $this->placementsRepository->setAllPlacementDefault();
	    $placement = $this->placementsRepository->setDefaultPlacement($placementsRequest->getPlacement_id());
	    if(!$placement) {
	        return new BadPlacementList();
        }
	    return $this->placementsRepository->getPlacements();
	}


}