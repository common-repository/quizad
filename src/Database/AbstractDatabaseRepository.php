<?php
namespace QuizAd\Database;

use wpdb;

/**
 * Abstract class wrapping core Wordpress wpdb functions.
 */
abstract class AbstractDatabaseRepository
{
	/** @var wpdb $wpdb */
	private $wpdb;

    /**
     * AbstractDatabaseRepository constructor.
     * @param $wpdb
     */
	function __construct( $wpdb )
	{
		$this->wpdb = $wpdb;
	}

	/**
	 * Wrapper for Wordpress's get_results - only for querying results.
	 *
	 * @param string $queryString
	 *
	 * @return array|object|null
     */
	protected function query( $queryString )
	{
		return $this->wpdb->get_results( $queryString );
	}

	/**
	 * Wrapper for Wordpress's query - executes update queries.
	 *
	 * @param string $queryString
	 *
	 * @return false|int
     */
	protected function executeRawQuery($queryString )
	{
		return $this->wpdb->query( $queryString );
	}

	/**
	 * Wrapper for Wordpress's update  - execute update, but using update method, not full query.
	 *
	 * @param $table
	 * @param $update
	 * @param $where
	 *
	 * @return false|int
	 */
	protected function update($table, $update, $where)
	{
		return $this->wpdb->update($table, $update, $where);
	}

	/**
	 * Wrapper for Wordpress's insert - execute insert, but using insert method, not full query.
	 *
	 * @param $table
	 * @param $insert
	 * @param null $format
	 *
	 * @return false|int
	 */
	protected function insert($table, $insert, $format = null)
	{
		return $this->wpdb->insert($table,$insert, $format);
	}
}