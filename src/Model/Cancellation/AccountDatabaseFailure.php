<?php

namespace QuizAd\Model\Cancellation;

class AccountDatabaseFailure
{
    protected $message;
    protected $statusCode;

    /**
     * RegistrationFailureResponse constructor.
     *
     * @param string $message
     * @param int    $statusCode
     */
    public function __construct($message = 'Unknown database value ', $statusCode = 500)
    {
        $this->message    = $message;
        $this->statusCode = $statusCode;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->statusCode;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return false
     */
    public function wasSuccessful()
    {
        return false;
    }
}