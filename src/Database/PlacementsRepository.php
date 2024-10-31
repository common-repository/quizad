<?php


namespace QuizAd\Database;

use QuizAd\Database\Tables\PlacementsTable as PTable;
use QuizAd\Model\Placements\Placement;
use QuizAd\Model\Placements\PlacementList;
use QuizAd\Model\Placements\QuizPlacementsRequest;
use QuizAd\Model\Placements\Website;

/**
 * Manages placements list.
 */
class PlacementsRepository extends AbstractDatabaseRepository
{
    protected $placementsTable;

    /**
     * {@inheritDoc}
     */
    public function __construct($wpdb)
    {
        parent::__construct($wpdb);
        $this->placementsTable = new PTable();
    }

    public function createTable()
    {
        $query = $this->placementsTable->createTableQuery();
        $this->executeRawQuery($query);
    }

    /**
     * @param Placement $placement
     *
     * @param Website $website
     *
     * @return int|false - The number of rows inserted, or false on error.
     */
    public function addPlacement(Placement $placement, Website $website)
    {
        $insert = [
            PTable::COL_ID             => $placement->getPlacementId(),
            PTable::COL_HTML_CODE      => $placement->getHtmlCode(),
            PTable::COL_HEADER_CODE    => $placement->getHeaderCode(),
            PTable::COL_WEBSITE_ID     => $website->getApplicationId(),
            PTable::COL_IS_DEFAULT     => $placement->getIsDefault(),
            PTable::COL_PLACEMENT_NAME => $placement->getPlacementName(),
            PTable::COL_QUIZ_SENTENCE  => $placement->getPlacementSentence(),
        ];

        return $this->insert($this->placementsTable->getFullTableName(), $insert);
    }

    /**
     * @return false|int
     */
    public function setAllPlacementDefault()
    {
        $update = [
            PTable::COL_IS_DEFAULT => 0
        ];
        $where  = [
            PTable::COL_IS_DEFAULT => 1
        ];

        return $this->update($this->placementsTable->getFullTableName(), $update, $where);
    }

    /**
     * @param $id
     * @return false|int
     */
    public function setDefaultPlacement($id)
    {
        $update = [PTable::COL_IS_DEFAULT => 1];
        $where  = [PTable::COL_ID => $id];

        return $this->update($this->placementsTable->getFullTableName(), $update, $where);
    }

    /**
     * @return array|object|null
     */
    public function removeAllPlacements()
    {
        $query = "TRUNCATE TABLE " . $this->placementsTable->getFullTableName();

        return $this->query($query);
    }

    /**
     * @param Placement $placement
     * @param Website $website
     * @return false|int
     */
    public function updatePlacement(Placement $placement, Website $website)
    {
        $update = [
            PTable::COL_ID             => $placement->getPlacementId(),
            PTable::COL_PLACEMENT_NAME => $placement->getPlacementName(),
            PTable::COL_HTML_CODE      => $placement->getHtmlCode(),
            PTable::COL_HEADER_CODE    => $placement->getHeaderCode(),
            PTable::COL_WEBSITE_ID     => $website->getApplicationId(),
        ];
        $where  = [];

        return $this->update($this->placementsTable->getFullTableName(), $update, $where);
    }

    /**
     * @return PlacementList
     */
    public function getPlacements()
    {
        $query   = "SELECT 
       		" . PTable::COL_ID . " , 
       		" . PTable::COL_PLACEMENT_NAME . " , 
       		" . PTable::COL_HTML_CODE . " ,
       		" . PTable::COL_HEADER_CODE . " ,
       		" . PTable::COL_QUIZ_SENTENCE . " ,
       		" . PTable::COL_IS_DEFAULT . " 
		FROM  " . $this->placementsTable->getFullTableName();
        $results = $this->query($query);

        if (count($results) === 0) {
            return new PlacementList();
        }
        $placementList = new PlacementList();
        foreach ($results as $result) {
            $placementList->addPlacement(new Placement(
                $result->placement_id,
                $result->placement_name,
                $result->html_code,
                $result->header_code,
                $result->quiz_sentence,
                $result->is_default
            ));
        }

        return $placementList;
    }

    /**
     * @return bool
     */
    public function dropTable()
    {
        $query = 'DROP TABLE IF EXISTS ' . $this->placementsTable->getFullTableName();
        $this->executeRawQuery($query);
        return true;
    }

    /**
     * Get Default Placement.
     * @return Placement
     */
    public function getDefaultPlacement()
    {
        $query  = "SELECT 
       		" . PTable::COL_ID . " , 
       		" . PTable::COL_PLACEMENT_NAME . " , 
       		" . PTable::COL_HTML_CODE . " ,
       		" . PTable::COL_HEADER_CODE . " ,
       		" . PTable::COL_QUIZ_SENTENCE . " ,
       		" . PTable::COL_IS_DEFAULT . " 
		FROM  " . $this->placementsTable->getFullTableName() . "
		WHERE " . PTable::COL_IS_DEFAULT . "=true";
        $result = $this->query($query)[0];
        return new Placement(
            $result->placement_id,
            $result->placement_name,
            $result->html_code,
            $result->header_code,
            $result->quiz_sentence,
            $result->is_default
        );
    }

    /**
     * Set placement sentence.
     * @param Placement $placement
     * @param QuizPlacementsRequest $quizPlacementsRequest
     * @return false|int
     */
    public function setPlacementSentence(Placement $placement, QuizPlacementsRequest $quizPlacementsRequest)
    {
        $update = [
            PTable::COL_QUIZ_SENTENCE => $quizPlacementsRequest->getPlacementsSentence(),
        ];

        $where = [
            PTable::COL_ID => $placement->getPlacementId()
        ];
        return $this->update($this->placementsTable->getFullTableName(), $update, $where);
    }
}