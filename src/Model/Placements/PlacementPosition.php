<?php

namespace QuizAd\Model\Placements;

class PlacementPosition
{
    protected $positionPlace;
    protected $positionPlaceId;
    protected $isExcluded;

    /**
     * PlacementPosition constructor.
     * @param      $positionPlace
     * @param int  $positionPlaceId
     * @param bool $isExcluded
     */
    public function __construct($positionPlace, $positionPlaceId = null, $isExcluded = false)
    {
        $this->positionPlace   = $positionPlace;
        $this->positionPlaceId = $positionPlaceId;
        $this->isExcluded      = $isExcluded;
    }

    /**
     * @return string
     */
    public function getPlace()
    {
        switch ($this->positionPlace)
        {
            case 'post':
                return 'post';
            case 'page':
                return 'page';
            case 'cat':
                return 'category';
            case 'front':
                return 'frontend';
            default:
                return '';
        }
    }

    /**
     * @return int|null
     */
    public function getPlaceId()
    {
        return $this->positionPlaceId;
    }

    /**
     * @return bool
     */
    public function isExcluded()
    {
        return $this->isExcluded;
    }


}