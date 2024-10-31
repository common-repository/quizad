<?php


namespace QuizAd\Controller\View;

use QuizAd\Controller\AbstractViewController;
use QuizAd\Model\View\PlacementsView;
use QuizAd\Model\View\ViewModel;
use QuizAd\Service\OAuth2\OAuth2Service;
use QuizAd\Service\Placements\PlacementsService;
use QuizAd\Service\Registration\CategoriesService;

class PlacementsViewController extends AbstractViewController
{
    protected $views;
    protected $credentialsService;
    protected $categoriesService;
    protected $placementsService;
    protected $reCaptchaToken;
    protected $reCaptchaUrl;

    /**
     * DashViewController constructor.
     *
     * @param array $views - list of views from templates.php
     * @param OAuth2Service $oAuth2Service
     * @param string $reCaptchaToken - reCaptcha token (public one) passed to api.js
     * @param string $reCaptchaUrl
     * @param CategoriesService $categoriesService
     * @param PlacementsService $placementsService
     * @param                   $pluginVersion
     */
    public function __construct(
        $views,
        OAuth2Service $oAuth2Service,
        $reCaptchaToken,
        $reCaptchaUrl,
        CategoriesService $categoriesService,
        PlacementsService $placementsService,
        $pluginVersion
    )
    {
        $this->views              = $views;
        $this->credentialsService = $oAuth2Service;
        $this->reCaptchaToken     = $reCaptchaToken;
        $this->reCaptchaUrl       = $reCaptchaUrl;
        $this->categoriesService  = $categoriesService;
        $this->placementsService  = $placementsService;
        $this->setVersion($pluginVersion);
    }

    /**
     * {@inheritdoc}
     */
    protected function render()
    {
        $clientCredentials = $this->credentialsService->getCredentials();
        if (!$clientCredentials->hasCredentials()) {
            return new ViewModel($this->views['permission'], []);
        }

//        if ($clientCredentials->hasValidToken()) {
        $placementsView = new PlacementsView();
        $placements     = $this->placementsService->getPlacements();
        $placementsView->setPlacements($placements);
        $placementProperties = $this->placementsService->getPlacementProperties();
        $placementsView->setWebsite($placementProperties);
        return new ViewModel($this->views['placements'], $placementsView);
//        }
//
//        // necessary when api return error responses
//        $categories = $this->categoriesService->getCategories();
//        return new ViewModel($this->views['permission'], $categories);
    }

    public function addScripts()
    {
        $this->addPluginScript('/assets/js/placements.js');
        $this->addPluginScript('/assets/js/reCaptchaToken.js');
        $this->addPluginScript('/assets/js/email.js');
        $this->addPluginAssetScript(
            $this->reCaptchaUrl . '?render=' . $this->reCaptchaToken . '',
            false
        );
    }
}