<?php

namespace QuizAd\Database;

use QuizAd\Database\Tables\PlacementsPositionsTable as PPTable;
use QuizAd\Model\Placements\Placement;
use QuizAd\Model\Placements\PlacementPosition;
use QuizAd\Model\Placements\Website;

/**
 * Manages placements list.
 */
class PlacementsPositionsRepository extends AbstractDatabaseRepository
{
    protected $placementsPositionsTable;

    /**
     * {@inheritDoc}
     */
    public function __construct($wpdb)
    {
        parent::__construct($wpdb);
        $this->placementsPositionsTable = new PPTable();
    }

    /**
     * Create Table Query
     * @return void
     */
    public function createTable()
    {
        $query = $this->placementsPositionsTable->createTableQuery();
        $this->executeRawQuery($query);
    }

    /**
     * @param Placement         $placement
     *
     * @param Website           $website
     *
     * @param PlacementPosition $placementPosition
     *
     * @return int|false - The number of rows inserted, or false on error.
     */
    public function addPlacement(Placement $placement, Website $website, PlacementPosition $placementPosition)
    {
        $insert = [
            PPTable::COL_ID                => $placement->getPlacementId(),
            PPTable::COL_PLACEMENT_ID      => $placement->getPlacementId(),
            PPTable::COL_WEBSITE_ID        => $website->getApplicationId(),
            PPTable::COL_POSITION_PLACE    => $placementPosition->getPlace(),
            PPTable::COL_POSITION_PLACE_ID => $placementPosition->getPlaceId(),
            PPTable::COL_IS_EXCLUDED       => $placementPosition->isExcluded(),
        ];

        return $this->insert($this->placementsPositionsTable->getFullTableName(), $insert);
    }

    /**
     * @return bool
     */
    public function dropTable()
    {
        $query = 'DROP TABLE IF EXISTS ' . $this->placementsPositionsTable->getFullTableName();
        $this->executeRawQuery($query);
        return true;
    }
}