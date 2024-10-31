<?php

namespace QuizAd\Database\Tables;

/**
 * Class representing model of placements table.
 */
class PlacementsTable extends AbstractTable
{
    protected $websitesTable;

    public function __construct()
    {
        $this->entity        = 'placements';
        $this->websitesTable = new WebsitesTable();
    }

    const COL_ID             = 'placement_id';
    const COL_PLACEMENT_NAME = 'placement_name';
    const COL_HTML_CODE      = 'html_code';
    const COL_HEADER_CODE    = 'header_code';
    const COL_WEBSITE_ID     = 'website_id';
    const COL_IS_DEFAULT     = 'is_default';
    const COL_QUIZ_SENTENCE  = 'quiz_sentence';

    public function createTableQuery()
    {
        return /** @lang MySQL */ "CREATE TABLE IF NOT EXISTS " . $this->getFullTableName() . " (
	          " . self::COL_ID . "             INT NOT NULL PRIMARY KEY
	        , " . self::COL_PLACEMENT_NAME . " TEXT NULL
	        , " . self::COL_HTML_CODE . "      TEXT NULL
            , " . self::COL_HEADER_CODE . "    TEXT NULL
            , " . self::COL_QUIZ_SENTENCE . " INT DEFAULT 10
			, " . self::COL_IS_DEFAULT . " BOOLEAN NOT NULL DEFAULT FALSE
			, " . self::COL_WEBSITE_ID . "     VARCHAR(36) NOT NULL " .
            $this->websitesTable->getFkReference(WebsitesTable::COL_ID) . "
		);";
    }
}