<?php


namespace QuizAd\Controller\View;


use QuizAd\Controller\AbstractViewController;
use QuizAd\Model\View\PlacementsView;
use QuizAd\Model\View\ViewModel;
use QuizAd\Service\OAuth2\OAuth2Service;
use QuizAd\Service\Placements\PlacementsService;
use QuizAd\Service\Registration\CategoriesService;
use QuizAd\Service\Statistics\StatisticsService;

class StatisticsViewController extends AbstractViewController
{
    protected $views;
    protected $credentialsService;
    protected $categoriesService;
    protected $placementsService;
    protected $statisticsService;

    /**
     * DashViewController constructor.
     *
     * @param array             $views - list of views from templates.php
     * @param OAuth2Service     $oAuth2Service
     * @param CategoriesService $categoriesService
     * @param PlacementsService $placementsService
     * @param StatisticsService $statisticsService
     * @param                   $pluginVersion
     */
    public function __construct(
        $views,
        OAuth2Service $oAuth2Service,
        CategoriesService $categoriesService,
        PlacementsService $placementsService,
        StatisticsService $statisticsService,
        $pluginVersion
    )
    {
        $this->views              = $views;
        $this->credentialsService = $oAuth2Service;
        $this->categoriesService  = $categoriesService;
        $this->placementsService  = $placementsService;
        $this->statisticsService  = $statisticsService;
        $this->setVersion($pluginVersion);
    }

    /**
     * {@inheritdoc}
     */
    protected function render()
    {
        $clientCredentials = $this->credentialsService->getCredentials();

        if (!$clientCredentials->hasCredentials())
        {
            $categories = $this->categoriesService->getCategories();

            return new ViewModel($this->views['permission'], $categories);
        }
        if (!$clientCredentials->hasAnyToken())
        {
            return new ViewModel($this->views['email'], []);
        }

        if ($clientCredentials->hasValidToken() && $clientCredentials->hasCredentials())
        {
            $statisticsView = new PlacementsView();
//			$statistics     = $this->statisticsService->getStatistics();
//			$statisticsView->setStatistics($statistics->getStatisticsList());

            return new ViewModel($this->views['statistics'], $statisticsView);
        }

        // maybe another option ? error page ?
        $categories = $this->categoriesService->getCategories();

        // necessary?
        return new ViewModel($this->views['permission'], $categories);
    }

    public function addScripts()
    {
        $this->addPluginScript('/assets/js/Chart.min.js');
        $this->addPluginScript('/assets/js/statistics.js');
    }
}