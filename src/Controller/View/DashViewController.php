<?php

namespace QuizAd\Controller\View;

use QuizAd\Controller\AbstractViewController;
use QuizAd\Service\OAuth2\OAuth2Service;
use QuizAd\Service\Placements\DisplayPlacementsService;
use QuizAd\Service\Registration\CategoriesService;
use QuizAd\Model\View\ViewModel;

class DashViewController extends AbstractViewController
{
    protected $views;
    protected $credentialsService;
    protected $categoriesService;
    protected $reCaptchaToken;
    protected $reCaptchaUrl;
    private   $displayPlacementsService;

    /**
     * DashViewController constructor.
     *
     * @param array $views - list of views from templates.php
     * @param OAuth2Service $oAuth2Service
     * @param CategoriesService $categoriesService
     * @param DisplayPlacementsService $displayPlacementsService
     * @param string $reCaptchaToken - reCaptcha token (public one) passed to api.js
     * @param string $reCaptchaUrl
     * @param string $pluginVersion - version to avoid/enable hitting cache
     */
    public function __construct(
        $views,
        OAuth2Service $oAuth2Service,
        CategoriesService $categoriesService,
        DisplayPlacementsService $displayPlacementsService,
        $reCaptchaToken,
        $reCaptchaUrl,
        $pluginVersion
    )
    {
        $this->views              = $views;
        $this->credentialsService = $oAuth2Service;
        $this->categoriesService  = $categoriesService;
        $this->reCaptchaToken     = $reCaptchaToken;
        $this->reCaptchaUrl       = $reCaptchaUrl;
        $this->setVersion($pluginVersion);
        $this->displayPlacementsService = $displayPlacementsService;
    }

    /**
     * {@inheritdoc}
     */
    protected function render()
    {
        $clientCredentials = $this->credentialsService->getCredentials();

        if (!$clientCredentials->hasCredentials()) {
            $categories = $this->categoriesService->getEmptyCategories();
            return new ViewModel($this->views['login_splash'], $categories);
        }

        $website = $this->displayPlacementsService->getCurrentWebsite();

        return new ViewModel($this->views['settings_splash'], [
            'website' => [
                'email'      => $website->getApplicationEmail(),
                'categories' => $website->getUserCategories()
            ]
        ]);
    }

    public function addScripts()
    {
        $this->addPluginScript('/assets/js/reCaptchaToken.js');
        $this->addPluginScript('/assets/js/registration.js');
        $this->addPluginScript('/assets/js/login.js');
        $this->addPluginAssetScript(
            $this->reCaptchaUrl . '?render=' . $this->reCaptchaToken . '',
            false
        );
    }
}