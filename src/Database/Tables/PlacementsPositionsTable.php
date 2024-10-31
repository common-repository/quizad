<?php

namespace QuizAd\Database\Tables;

/**
 * Class representing model of placements table.
 */
class PlacementsPositionsTable extends AbstractTable
{
    protected $websitesTable;
    protected $placementsTable;

    public function __construct()
    {
        $this->entity          = 'placements_positions';
        $this->websitesTable   = new WebsitesTable();
        $this->placementsTable = new PlacementsTable();
    }

    const COL_ID                = 'placement_position_id';
    const COL_WEBSITE_ID        = 'website_id';
    const COL_PLACEMENT_ID      = 'placement_id';
    const COL_POSITION_PLACE    = 'position_place';
    const COL_POSITION_PLACE_ID = 'position_place_id';
    const COL_IS_EXCLUDED       = 'is_excluded';

    public function createTableQuery()
    {
        return /** @lang MySQL */ "CREATE TABLE IF NOT EXISTS " . $this->getFullTableName() . " (
	          " . self::COL_ID . "             INT NOT NULL PRIMARY KEY
            , " . self::COL_WEBSITE_ID . "     VARCHAR(36) NOT NULL " . $this->websitesTable->getFkReference(WebsitesTable::COL_ID) . "
            , " . self::COL_PLACEMENT_ID . " INT NOT NULL " . $this->placementsTable->getFkReference(PlacementsTable::COL_ID) . "
            , " . self::COL_POSITION_PLACE . " VARCHAR(10) NOT NULL
            , " . self::COL_POSITION_PLACE_ID . " INT
			, " . self::COL_IS_EXCLUDED . " BOOLEAN NOT NULL DEFAULT FALSE
		);";
    }
}