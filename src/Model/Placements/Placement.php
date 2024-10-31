<?php


namespace QuizAd\Model\Placements;


class Placement
{
    protected $headerCode;
    protected $htmlCode;
    protected $placementId;
    protected $placementName;
    protected $sentence;
    protected $isDefault;

    /**
     * Placement constructor.
     *
     * @param      $placementId
     * @param      $placementName
     * @param      $htmlCode
     * @param $headerCode
     * @param      $sentence
     * @param bool $isDefault
     */
    public function __construct($placementId, $placementName, $htmlCode, $headerCode, $sentence, $isDefault = false)
    {
        $this->placementId   = $placementId;
        $this->placementName = $placementName;
        $this->headerCode    = $headerCode;
        $this->htmlCode      = $htmlCode;
        $this->isDefault     = (bool)$isDefault;
        $this->sentence      = (int)$sentence;
    }

    /**
     * @return string
     */
    public function getHeaderCode()
    {
        return $this->headerCode;
    }

    /**
     * @return string
     */
    public function getHtmlCode()
    {
        return $this->htmlCode;
    }

    /**
     * @return string|void
     */
    public function getPlacementId()
    {
        return esc_attr($this->placementId);
    }

    /**
     * @return string
     */
    public function getPlacementName()
    {
        return esc_attr($this->placementName);
    }

    /**
     * @return string|void
     */
    public function getIsDefault()
    {
        return esc_attr($this->isDefault);
    }

    /**
     * @param bool $isDefault
     */
    public function setIsDefault($isDefault)
    {
        $this->isDefault = esc_attr($isDefault);
    }

    /**
     * @return int
     */
    public function getPlacementSentence()
    {
        return $this->sentence;
    }

}