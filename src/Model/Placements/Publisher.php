<?php


namespace QuizAd\Model\Placements;


class Publisher
{
    protected $placements;
    protected $publisherId;
    protected $headerCode;
    protected $sentence;

    /**
     * SuccessfulApiPlacements constructor.
     *
     * @param array $apiPublisher
     */
    public function __construct($apiPublisher = [])
    {
        $list     = new PlacementList();
        $headCode = '';
        foreach ($apiPublisher['placements'] as $apiPlacement) {
            $splitCode = explode('<!-- Section <body> tag -->', $apiPlacement['code']['split']);
            $headCode  = $splitCode[0];
            $splitBody = explode('<!-- On End Section <body> -->', $splitCode[1] ?? '');

            $list->addPlacement(new Placement(
                $apiPlacement['id'],
                $apiPlacement['name'],
                $splitBody[0] ?? '',
                $headCode,
                $apiPlacement['sentences']
            ));
        }

        $list->setupDefaultPlacement();
        $defaultPlacement = $list->getDefaultPlacement();
        if ($defaultPlacement)
            $this->publisherId = $defaultPlacement->getPlacementId();
        $this->headerCode = $headCode;
        $this->placements = $list;
    }

    /**
     * @return int
     */
    public function getPublisherId()
    {
        return intval($this->publisherId);
    }

    /**
     * @return string
     */
    public function getHeaderCode()
    {
        return $this->headerCode;
    }

    /**
     * @return PlacementList
     */
    public function getPlacementList()
    {
        return $this->placements;
    }

    /**
     * @return int
     */
    public function getSentence()
    {
        return $this->sentence;
    }


}