<?php

namespace QuizAd\Model\Cancellation;

use QuizAd\Model\RestResponseInterface;

/**
 * Response, that occurs, when account have been updated successfully.
 */
class AccountSuccessfulResponse implements RestResponseInterface
{
    protected $message = 'Account updated';
    protected $code    = 200;

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return bool
     */
    public function wasSuccessful()
    {
        return true;
    }
}