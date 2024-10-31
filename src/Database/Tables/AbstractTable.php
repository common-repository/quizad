<?php

namespace QuizAd\Database\Tables;

/**
 * Abstract class representation.
 */
class AbstractTable
{
    const TABLE_PREFIX = 'quizAd_froc';
    /** @var string $entity - entity is like a part of domain, like 'placements', 'credentials'
     */
    protected $entity;

    public function getFullTableName()
    {
        return self::TABLE_PREFIX . '_' . $this->entity;
    }

    /**
     * Return references query part for table DDL definition.
     *
     * @param string $columnName
     *
     * @return string - REFERENCES query part
     */
    public function getFkReference($columnName)
    {
        return "REFERENCES " . $this->getFullTableName() . "(" . $columnName . ")";
    }
}