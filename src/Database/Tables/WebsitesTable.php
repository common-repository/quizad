<?php

namespace QuizAd\Database\Tables;

/**
 * Model representing websites table.
 */
class WebsitesTable extends AbstractTable
{
    protected $credentialsTable;

    const COL_ID                = 'application_id';
    const COL_EMAIL             = 'application_email';
    const COL_CATEGORIES        = 'application_categories';
    const COL_HEADER_CODE       = 'header_code';
    const COL_DISPLAY_POSITION  = 'display_position';
    const COL_EXCLUDED_POSITION = 'excluded_position';
    const COL_QUIZ_SENTENCE     = 'quiz_sentence';
    const COL_WP_ID             = 'wp_id';
    const COL_ACCOUNTS_ID       = 'accounts_id';
    const COL_BLOG_ID           = 'blog_id';

    public function __construct()
    {
        $this->entity           = 'websites';
        $this->credentialsTable = new CredentialsTable();
    }

    public function createTableQuery()
    {
        return /** @lang MySQL */ "CREATE TABLE IF NOT EXISTS " . $this->getFullTableName() . " (
    		  " . self::COL_ID . "               VARCHAR(36) NOT NULL PRIMARY KEY
	        , " . self::COL_EMAIL . "  TEXT NULL
	        , " . self::COL_CATEGORIES . "  TEXT NULL
	        , " . self::COL_HEADER_CODE . "      TEXT NULL
	        , " . self::COL_DISPLAY_POSITION . " TEXT NULL
	        , " . self::COL_EXCLUDED_POSITION . " TEXT NULL
	        , " . self::COL_QUIZ_SENTENCE . "    INT(7) DEFAULT 10
	        , " . self::COL_WP_ID . "            INT NULL
	        , " . self::COL_BLOG_ID . "     	 INT NULL 
	        , " . self::COL_ACCOUNTS_ID . "      INT NULL 
	            					" . $this->credentialsTable->getFkReference(CredentialsTable::COL_ID) . "
		);";
    }
}