<?php

namespace QuizAd\Service\Registration;

use QuizAd\Model\Credentials\Credentials;
use QuizAd\Model\Registration\API\Registration\RegistrationFailureResponse;
use QuizAd\Model\Registration\API\Registration\CategoriesCollection;
use QuizAd\Service\OAuth2\OAuth2Service;


class CategoriesService
{
    /**
     * @var CategoriesApiClient
     */
    protected $categoriesApiClient;
    /**
     * @var OAuth2Service
     */
    protected $OAuth2TokenService;

    /**
     * CategoriesService constructor.
     *
     * @param CategoriesApiClient $categoriesApiClient
     * @param OAuth2Service       $OAuth2TokenService
     */
    public function __construct(CategoriesApiClient $categoriesApiClient, OAuth2Service $OAuth2TokenService)
    {
        $this->categoriesApiClient = $categoriesApiClient;
        $this->OAuth2TokenService  = $OAuth2TokenService;
    }

    /**
     *
     * @return CategoriesCollection
     */
    public function getCategories()
    {
        $response = $this->categoriesApiClient->categoryApp();

        if ($response instanceof RegistrationFailureResponse)
        {
            return new CategoriesCollection([]);
        }

        return $response;
    }

    /**
     * @param Credentials $clientId
     * @return CategoriesCollection|RegistrationFailureResponse
     */
    public function getPrivateCategories(Credentials $clientId)
    {
        $response = $this->categoriesApiClient->privateCategoryApp($clientId, $this->OAuth2TokenService);

        if ($response instanceof RegistrationFailureResponse)
        {
            return new CategoriesCollection([]);
        }

        return $response;
    }

    /**
     * @return CategoriesCollection
     */
    public function getEmptyCategories()
    {
        return new CategoriesCollection([]);
    }
}