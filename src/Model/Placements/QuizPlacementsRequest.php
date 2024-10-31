<?php

namespace QuizAd\Model\Placements;

use QuizAd\Model\AbstractValidatableModel;

class QuizPlacementsRequest extends AbstractValidatableModel
{
    protected $placementsSentence;

    /**
     * DisplayPlacementsRequest constructor.
     *
     * @param int $placementsSentence
     */
    public function __construct($placementsSentence)
    {
        parent::__construct();
        $this->placementsSentence = (int)$placementsSentence;
    }

    protected function validateFields()
    {
        if (!(is_int($this->placementsSentence)))
        {
            $this->addErrorMessage("Placements sentence were not correct!");
        }
        if ($this->placementsSentence < 2)
        {
            $this->addErrorMessage("Placements sentence must be higher than 2");
        }
    }

    /**
     * @return int
     */
    public function getPlacementsSentence()
    {
        return $this->placementsSentence;
    }


}