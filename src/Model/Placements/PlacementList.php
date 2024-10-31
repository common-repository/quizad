<?php

namespace QuizAd\Model\Placements;

class PlacementList
{
    /**
     * @var Placement[]
     */
    protected $placements;

    /**
     * PlacementList constructor.
     */
    public function __construct()
    {
        $this->placements = [];
    }

    /**
     * @param Placement $placement
     */
    public function addPlacement(Placement $placement)
    {
        $this->placements [] = $placement;
    }

    /**
     * @return bool
     */
    public function hasPlacements()
    {
        return count($this->placements) > 0;
    }

    /**
     * @return Placement[]
     */
    public function getPlacements()
    {
        return $this->placements;
    }

    /**
     * sort placement and set default (is_default(true)) on the top.
     */
    public function setupDefaultPlacement()
    {
        usort($this->placements, function (Placement $a, Placement $b) {
            if ($a->getPlacementId() === $b->getPlacementId())
            {
                return 0;
            }
            return ($a->getPlacementId() < $b->getPlacementId()) ? -1 : 1;
        });

        if (!empty($this->placements[0]) && $this->placements[0] instanceof Placement)
        {
            $this->placements[0]->setIsDefault(true);
        }
    }

    /**
     * @return null|Placement
     */
    public function getDefaultPlacement()
    {
        foreach ($this->placements as $placement)
        {
            if ($placement->getIsDefault() == true)
            {
                return $placement;
            }
        }
        return null;
    }

    /**
     * @return bool
     */
    public function wasSuccessful()
    {
        return true;
    }
}