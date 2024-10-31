<?php

namespace QuizAd\Database;

use QuizAd\Model\Credentials\Credentials;
use QuizAd\Model\Placements\DisplayPosition;
use QuizAd\Model\Placements\Publisher;
use QuizAd\Model\Placements\Website;
use QuizAd\Database\Tables\WebsitesTable as WTable;

/**
 * Manages websites. Creates with Multi-User setup in mind.
 */
class WebsiteRepository extends AbstractDatabaseRepository
{
    protected $websitesTable;

    /**
     * WebsiteRepository constructor.
     * @param $wpdb
     */
    public function __construct($wpdb)
    {
        parent::__construct($wpdb);
        $this->websitesTable = new WTable();
    }

    public function tableExists()
    {
        $query  = /** @lang MySQL */
            "SELECT 1 as \"exists\" 
			FROM information_schema.tables 
			WHERE table_name = '" . $this->websitesTable->getFullTableName() . "' limit 1;";
        $tables = $this->query($query);

        return is_array($tables) && !empty($tables) && $tables[0]->exists;
    }

    public function createTable()
    {
        $query = $this->websitesTable->createTableQuery();
        $this->executeRawQuery($query);
    }

    /**
     * @param Website $website
     *
     * @param Credentials $credentials
     *
     * @return int|false - The number of rows inserted, or false on error.
     */
    public function addWebsite(Website $website, Credentials $credentials)
    {
        $insert = [
            WTable::COL_ID          => $website->getApplicationId(),
            WTable::COL_EMAIL       => $website->getApplicationEmail(),
            WTable::COL_CATEGORIES  => $website->getApplicationCategories(),
            WTable::COL_ACCOUNTS_ID => $credentials->getId(),
            WTable::COL_WP_ID       => $website->getWpId(),
            WTable::COL_BLOG_ID     => $website->getWordpressBlogId(),
        ];

        return $this->insert($this->websitesTable->getFullTableName(), $insert);
    }

    /**
     * TODO: decide about additional functionality, relation website with credentials - multi site
     * @param Credentials $credentials
     * @return Website
     */
    public function getWebsite(Credentials $credentials)
    {
        $query   = "SELECT 
       		" . WTable::COL_ID . " 
       		," . WTable::COL_EMAIL . " 
       		," . WTable::COL_CATEGORIES . " 
		FROM  " . $this->websitesTable->getFullTableName() . " 
		WHERE " . WTable::COL_ACCOUNTS_ID . " = '1' ORDER BY " . WTable::COL_ID . " DESC LIMIT 1";
        $results = $this->query($query);

        if (count($results) === 0) {
            return new Website();
        }
        $row = $results[0];

        return new Website($row->application_id, $row->application_email,
            $row->application_categories, get_current_user_id());
    }

    /**
     * @param Publisher $publisher , Website $website
     *
     * @param Website $website
     *
     * @return int|false - The number of rows inserted, or false on error.
     */
    public function setHeaderCode(Publisher $publisher, Website $website)
    {
        $update = [
            WTable::COL_HEADER_CODE => $publisher->getHeaderCode()
        ];
        $where  = [
            WTable::COL_ID => $website->getApplicationId()
        ];

        return $this->update($this->websitesTable->getFullTableName(), $update, $where);
    }

    /**
     * Get wbsite object with proper header code for end-user.
     *
     * @param Website $website
     *
     * @return Website
     */
    public function getUsersWebsite(Website $website)
    {
        $query   = "SELECT 
       		" . WTable::COL_HEADER_CODE . " 
		FROM  " . $this->websitesTable->getFullTableName() . " 
		WHERE  " . WTable::COL_ID . " ='{$website->getApplicationId()}'
		LIMIT 1";
        $results = $this->query($query);
        if (count($results) === 0) {
            return new Website();
        }
        $row = $results[0];

        return new Website('', '', '', '', '', '', '', $row->{WTable::COL_HEADER_CODE});
    }

    /**
     * @param Website $website
     *
     * @param DisplayPosition $displayPosition
     *
     * @return int|false - The number of rows inserted, or false on error.
     */
    public function setPlacementLocations(Website $website, DisplayPosition $displayPosition)
    {
        $update = [
            WTable::COL_DISPLAY_POSITION => $displayPosition->getGetPosition(),
        ];

        $where = [
            WTable::COL_ID => $website->getApplicationId()
        ];

        return $this->update($this->websitesTable->getFullTableName(), $update, $where);
    }

    /**
     * @param Website $website
     *
     * @param DisplayPosition $displayPosition
     *
     * @return int|false - The number of rows inserted, or false on error.
     */
    public function setPlacementExcludedLocations(Website $website, DisplayPosition $displayPosition)
    {
        $update = [
            WTable::COL_EXCLUDED_POSITION => $displayPosition->getGetPosition(),
        ];

        $where = [
            WTable::COL_ID => $website->getApplicationId()
        ];

        return $this->update($this->websitesTable->getFullTableName(), $update, $where);
    }

    /**
     * Get all display positions for selected website.
     *
     * @param Website $website
     *
     * @return Website
     */
    public function getWebsiteWithProperties(Website $website)
    {
        $query   = "SELECT 
       			" . WTable::COL_ID . "
                ," . WTable::COL_EMAIL . " 
                ," . WTable::COL_CATEGORIES . " 
       			," . WTable::COL_BLOG_ID . " 
       			," . WTable::COL_DISPLAY_POSITION . " 
       			," . WTable::COL_EXCLUDED_POSITION . " 
       			," . WTable::COL_QUIZ_SENTENCE . "
       			," . WTable::COL_WP_ID . " 
			FROM  " . $this->websitesTable->getFullTableName() . " 
			WHERE " . WTable::COL_ID . " = '" . esc_sql($website->getApplicationId()) . "' 
			LIMIT 1";
        $results = $this->query($query);

        if (count($results) === 0) {
            return new Website();
        }

        $row = $results[0];
        return new Website(
            $row->application_id,
            $row->application_email,
            $row->application_categories,
            $row->wp_id,
            $row->blog_id,
            $row->display_position,
            (int)$row->quiz_sentence,
            '',
            $row->excluded_position);
    }

    /**
     * @return bool
     */
    public function dropTable()
    {
        $query = 'DROP TABLE IF EXISTS ' . $this->websitesTable->getFullTableName();
        $this->executeRawQuery($query);
        return true;
    }
}