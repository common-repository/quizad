<?php


namespace QuizAd\Model\Placements;


class Website
{
    protected $applicationId;
    protected $applicationEmail;
    protected $applicationCategories;
    protected $wpId;
    protected $wordpressBlogId;
    protected $displayPosition;
    protected $sentence;
    protected $headerCode;
    protected $excludePosition;

    /**
     * Website constructor.
     *
     * @param string $applicationId
     * @param string $applicationEmail
     * @param string $applicationCategories
     * @param string $wordpressUserId
     * @param string $wordpressBlogId
     * @param string $displayPosition
     * @param null   $excludePosition
     * @param int    $sentence
     * @param string $headerCode
     */
    public function __construct(
        $applicationId = '',
        $applicationEmail = '',
        $applicationCategories = '',
        $wordpressUserId = '',
        $wordpressBlogId = '',
        $displayPosition = null,
        $sentence = null,
        $headerCode = '',
        $excludePosition = null
    )
    {
        $this->applicationId         = $applicationId;
        $this->applicationEmail      = $applicationEmail;
        $this->applicationCategories = $applicationCategories;
        $this->wpId                  = $wordpressUserId;
        $this->displayPosition       = $displayPosition;
        $this->headerCode            = $headerCode;
        $this->wordpressBlogId       = $wordpressBlogId;
        $this->sentence              = $sentence;
        $this->excludePosition       = $excludePosition;
    }

    /**
     * @return string
     */
    public function getApplicationId()
    {
        return esc_attr($this->applicationId);
    }

    /**
     * @return string
     */
    public function getApplicationEmail()
    {
        return $this->applicationEmail;
    }

    /**
     * @return string
     */
    public function getApplicationCategories()
    {
        return implode(',', $this->applicationCategories);
    }

    /**
     * @return false|string[]
     */
    public function getUserCategories()
    {
        return explode(',', $this->applicationCategories);
    }

    /**
     * @return int
     */
    public function getWpId()
    {
        return intval($this->wpId);
    }

    /**
     * @return string|void
     */
    public function getDisplayPositions()
    {
        return esc_attr($this->displayPosition);
    }

    /**
     * @return string
     */
    public function getExcludePosition()
    {
        return (string)$this->excludePosition;
    }

    /**
     * @return string - containing html code.
     */
    public function getHeaderCode()
    {
        return $this->headerCode;
    }

    /**
     * @return string
     */
    public function getWordpressBlogId()
    {
        return esc_attr($this->wordpressBlogId);
    }

    /**
     * @return string|null
     */
    public function getDisplayPosition()
    {
        return esc_attr($this->displayPosition);
    }

    /**
     * @return int
     */
    public function getSentence()
    {
        return (int)esc_attr($this->sentence);
    }
}