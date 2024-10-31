<?php

namespace QuizAd\Database\Tables;


class CredentialsTable extends AbstractTable
{
    // Column definitions
    const COL_ID            = 'id';
    const COL_CLIENT_ID     = 'client_id';
    const COL_CLIENT_SECRET = 'client_secret';
    const COL_ACCESS_TOKEN  = 'access_token';
    const COL_EXPIRE_IN     = 'expire_in';
    const COL_PUBLISHER_ID  = 'publisher_id';

    /**
     * CredentialsTable constructor.
     */
    public function __construct()
    {
        $this->entity = 'credentials';
    }

    /**
     * Create Table Query
     * @return string
     */
    public function createTableQuery()
    {
        return /** @lang MySQL */ "CREATE TABLE IF NOT EXISTS " . $this->getFullTableName() . " (
	          " . self::COL_ID . "            int NOT NULL AUTO_INCREMENT PRIMARY KEY
	        , " . self::COL_CLIENT_ID . "     TEXT NOT NULL
	        , " . self::COL_CLIENT_SECRET . " TEXT NOT NULL
	        , " . self::COL_ACCESS_TOKEN . "  TEXT NULL
	        , " . self::COL_EXPIRE_IN . "     datetime NULL
	        , " . self::COL_PUBLISHER_ID . "  TEXT NULL
        )";
    }
}