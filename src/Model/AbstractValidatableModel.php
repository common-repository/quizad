<?php

namespace QuizAd\Model;


abstract class AbstractValidatableModel
{
    protected $result;

    public function __construct()
    {
        $this->result = new ValidationResult();
    }

    /**
     * In case of validation error add message.
     *
     * @param string $msg
     */
    public function addErrorMessage($msg)
    {
        $this->result->addErrorMessage($msg);
    }

    /**
     * Validate fields. Do not return anything. Use isValid method to check.
     */
    public function validate()
    {
        $this->validateFields();
        return $this->result;
    }

    /**
     * Validate fields. Do not return anything. Use isValid method to check.
     */
    protected abstract function validateFields();
}
