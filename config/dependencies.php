<?php

namespace QuizAd;

use QuizAd\Controller\Rest\AccessTokenRestController;
use QuizAd\Controller\Rest\DebugRestController;
use QuizAd\Controller\Rest\LoginRestController;
use QuizAd\Controller\Rest\PlacementLocationsRestController;
use QuizAd\Controller\Rest\PlacementQuizRestController;
use QuizAd\Controller\Rest\DefaultPlacementRestController;
use QuizAd\Controller\Rest\PlacementsExcludeRestController;
use QuizAd\Controller\Rest\PlacementsUpdateRestController;
use QuizAd\Controller\Rest\RegistrationRestController;
use QuizAd\Controller\Rest\ReinstallRestController;
use QuizAd\Controller\Rest\RemoveRestController;
use QuizAd\Controller\Rest\ResentEmailRestController;
use QuizAd\Controller\Rest\SearchRestController;
use QuizAd\Controller\Rest\StatisticsRestController;
use QuizAd\Controller\View\DashViewController;
use QuizAd\Container\Container;
use QuizAd\Controller\View\PlacementsViewController;
use QuizAd\Controller\View\StatisticsViewController;
use QuizAd\Database\CredentialsRepository;
use QuizAd\Database\PlacementsPositionsRepository;
use QuizAd\Database\PlacementsRepository;
use QuizAd\Database\WebsiteRepository;
use QuizAd\Service\Cancellation\AccountApiClient;
use QuizAd\Service\Debug\DebugApiClient;
use QuizAd\Service\Debug\DebugService;
use QuizAd\Service\OAuth2\OAuth2ApiClient;
use QuizAd\Service\OAuth2\OAuth2Service;
use QuizAd\Service\Placements\DisplayPlacementsService;
use QuizAd\Service\Placements\HeaderCodeApiService;
use QuizAd\Service\Placements\ListPlacementService;
use QuizAd\Service\Placements\PlacementsApiClient;
use QuizAd\Service\Placements\PlacementsService;
use QuizAd\Service\Placements\QuizPlacementsService;
use QuizAd\Service\Cancellation\AccountService;
use QuizAd\Service\Registration\CategoriesApiClient;
use QuizAd\Service\Registration\CategoriesService;
use QuizAd\Service\Registration\CredentialsApiClient;
use QuizAd\Service\Registration\IpProvider;
use QuizAd\Service\Registration\LoginService;
use QuizAd\Service\Registration\RegistrationService;
use QuizAd\Service\Statistics\StatisticsApiClient;
use QuizAd\Service\Statistics\StatisticsService;
use QuizAd\Service\Wordpress\PageService;

require __DIR__ . '/config.php';

/** @var Container $container */

/**
 * Set views pages.
 */
$container->set('views', function () {
    return require __DIR__ . '/templates.php';
});


$container->set("QuizAd\\Controller\\View\\DashViewController", function (Container $container) {
    $views              = $container->get('views');
    $credentialsService = $container->get('QuizAd\\Service\\OAuth2\\OAuth2Service');
    $categoriesService  = $container->get('QuizAd\\Service\\Registration\\CategoriesService');
    $container->get('QuizAd\\Service\\Placements\\DisplayPlacementsService');

    return new DashViewController(
        $views,
        $credentialsService,
        $categoriesService,
        $container->get('QuizAd\\Service\\Placements\\DisplayPlacementsService'),
        $container->get('config.plugin.api.recaptcha.token'),
        $container->get('config.plugin.api.recaptcha.url'),
        $container->get('config.plugin.api.version')
    );
});

$container->set("QuizAd\\Controller\\View\\PlacementsViewController", function (Container $container) {
    $views              = $container->get('views');
    $credentialsService = $container->get('QuizAd\\Service\\OAuth2\\OAuth2Service');
    $categoriesService  = $container->get('QuizAd\\Service\\Registration\\CategoriesService');
    $placementsService  = $container->get('QuizAd\\Service\\Placements\\PlacementsService');

    return new PlacementsViewController(
        $views,
        $credentialsService,
        $container->get('config.plugin.api.recaptcha.token'),
        $container->get('config.plugin.api.recaptcha.url'),
        $categoriesService,
        $placementsService,
        $container->get('config.plugin.api.version'));
});
$container->set("QuizAd\\Controller\\View\\StatisticsViewController", function (Container $container) {
    $views              = $container->get('views');
    $credentialsService = $container->get('QuizAd\\Service\\OAuth2\\OAuth2Service');
    $categoriesService  = $container->get('QuizAd\\Service\\Registration\\CategoriesService');
    $placementsService  = $container->get('QuizAd\\Service\\Placements\\PlacementsService');
    $statisticsService  = $container->get('QuizAd\\Service\\Statistics\\StatisticsService');

    return new StatisticsViewController(
        $views,
        $credentialsService,
        $categoriesService,
        $placementsService,
        $statisticsService,
        $container->get('config.plugin.api.version'));
});


$container->set('QuizAd\\Service\\Registration\\CategoriesApiClient', function ($container) {
    $apiHost = $container->get('config.plugin.api.host');

    return new CategoriesApiClient($apiHost);
});
$container->set('QuizAd\\Service\\Registration\\CategoriesService', function (Container $container) {
    return new CategoriesService(
        $container->get('QuizAd\\Service\\Registration\\CategoriesApiClient'),
        $container->get('QuizAd\\Service\\OAuth2\\OAuth2Service')
    );
});
$container->set('QuizAd\\Service\\Placements\\HeaderCodeApiService', function (Container $container) {
    return new HeaderCodeApiService(
        $container->get('QuizAd\\Repository\\CredentialsRepository'),
        $container->get('QuizAd\\Repository\\WebsiteRepository')
    );
});

$container->set('QuizAd\\Service\\Registration\\CredentialsApiClient', function (Container $container) {
    $apiHost = $container->get('config.plugin.api.host');

    return new CredentialsApiClient($apiHost);
});
$container->set('QuizAd\\Service\\Registration\\IpProvider', function ($container) {
    return new IpProvider();
});

$container->set('QuizAd\\Service\\Registration\\RegistrationService', function (Container $container) {
    return new RegistrationService(
        $container->get('QuizAd\\Repository\\CredentialsRepository'),
        $container->get('QuizAd\\Repository\\WebsiteRepository'),
        $container->get('QuizAd\\Service\\Registration\\CredentialsApiClient'),
        $container->get('QuizAd\\Service\\Registration\\IpProvider'),
        $container->get('config.plugin.scope')
    );
});

$container->set('QuizAd\\Service\\Registration\\LoginService', function (Container $container) {
    return new LoginService(
        $container->get('QuizAd\\Repository\\CredentialsRepository'),
        $container->get('QuizAd\\Repository\\WebsiteRepository'),
        $container->get('QuizAd\\Service\\Registration\\CredentialsApiClient'),
        $container->get('QuizAd\\Service\\Registration\\IpProvider'),
        $container->get('config.plugin.scope')
    );
});


$container->set('QuizAd\\Service\\OAuth2\\OAuth2ApiClient', function (Container $container) {
    $apiHost = $container->get('config.plugin.api.host');

    return new OAuth2ApiClient($apiHost);
});


$container->set('QuizAd\\Service\\OAuth2\\OAuth2Service', function (Container $container) {
    return new OAuth2Service(
        $container->get('QuizAd\\Repository\\CredentialsRepository'),
        $container->get('QuizAd\\Service\\OAuth2\\OAuth2ApiClient')
    );
});


$container->set('QuizAd\\Controller\\Rest\\RegistrationRestController', function (Container $container) {
    return new RegistrationRestController(
        $container->get('QuizAd\\Service\\Registration\\RegistrationService')
    );
});

$container->set('QuizAd\\Controller\\Rest\\LoginRestController', function (Container $container) {
    return new LoginRestController(
        $container->get('QuizAd\\Service\\Registration\\LoginService')
    );
});

$container->set('QuizAd\\Controller\\Rest\\AccessTokenRestController', function (Container $container) {
    return new AccessTokenRestController(
        $container->get('QuizAd\\Service\\Registration\\LoginService')
    );
});

$container->set('QuizAd\\Controller\\Rest\\ResentEmailRestController', function (Container $container) {
    return new ResentEmailRestController(
        $container->get('QuizAd\\Service\\Registration\\RegistrationService')
    );
});

$container->set('QuizAd\\Controller\\Rest\\StatisticsRestController', function (Container $container) {
    return new StatisticsRestController(
        $container->get('QuizAd\\Service\\Statistics\\StatisticsService')
    );
});

$container->set('QuizAd\\Service\\Placements\\DisplayPlacementsService', function (Container $container) {
    return new DisplayPlacementsService(
        $container->get('QuizAd\\Repository\\WebsiteRepository'),
        $container->get('QuizAd\\Repository\\CredentialsRepository')
    );
});

$container->set('QuizAd\\Service\\Cancellation\\AccountApiClient', function (Container $container) {
    $apiHost = $container->get('config.plugin.api.host');
    return new AccountApiClient($apiHost);
});

$container->set('QuizAd\\Service\\Cancellation\\AccountService', function (Container $container) {
    return new AccountService(
        $container->get('QuizAd\\Repository\\CredentialsRepository'),
        $container->get('QuizAd\\Repository\\WebsiteRepository'),
        $container->get('QuizAd\\Repository\\PlacementsRepository'),
        $container->get('QuizAd\\Repository\\PlacementsPositionsRepository'),
        $container->get('QuizAd\\Service\\OAuth2\\OAuth2Service'),
        $container->get('QuizAd\\Service\\Cancellation\\AccountApiClient')
    );
});

$container->set('QuizAd\\Controller\\Rest\\PlacementLocationsRestController', function (Container $container) {
    return new PlacementLocationsRestController(
        $container->get('QuizAd\\Service\\Placements\\DisplayPlacementsService')
    );
});
$container->set('QuizAd\\Controller\\Rest\\PlacementsExcludeRestController', function (Container $container) {
    return new PlacementsExcludeRestController(
        $container->get('QuizAd\\Service\\Placements\\DisplayPlacementsService')
    );
});
$container->set('QuizAd\\Controller\\Rest\\DefaultPlacementRestController', function (Container $container) {
    return new DefaultPlacementRestController(
        $container->get('QuizAd\\Service\\Placements\\ListPlacementService'),
        $container->get('QuizAd\\Service\\Registration\\RegistrationService')
    );
});
$container->set('QuizAd\\Service\\Placements\\ListPlacementService', function (Container $container) {
    return new ListPlacementService(
        $container->get('QuizAd\\Repository\\PlacementsRepository'),
        $container->get('QuizAd\\Service\\Placements\\PlacementsService')
    );
});

$container->set('QuizAd\\Controller\\Rest\\PlacementQuizRestController', function (Container $container) {
    return new PlacementQuizRestController(
        $container->get('QuizAd\\Service\\Placements\\QuizPlacementsService')
    );
});

$container->set('QuizAd\\Controller\\Rest\\SearchRestController', function () {
    return new SearchRestController();
});

$container->set('QuizAd\\Controller\\Rest\\ReinstallRestController', function (Container $container) {
    $accountService = $container->get('QuizAd\\Service\\Cancellation\\AccountService');
    return new ReinstallRestController($accountService);
});

$container->set('QuizAd\\Controller\\Rest\\RemoveRestController', function (Container $container) {
    $accountService = $container->get('QuizAd\\Service\\Cancellation\\AccountService');
    return new RemoveRestController($accountService);
});

$container->set('QuizAd\\Service\\Placements\\QuizPlacementsService', function (Container $container) {
    return new QuizPlacementsService(
        $container->get('QuizAd\\Repository\\PlacementsRepository'),
        $container->get('QuizAd\\Repository\\CredentialsRepository')
    );
});
$container->set('QuizAd\\Controller\\Rest\\PlacementsUpdateRestController', function (Container $container) {
    return new PlacementsUpdateRestController(
        $container->get('QuizAd\\Service\\Placements\\PlacementsService')
    );
});

$container->set('QuizAd\\Service\\Placements\\PlacementsApiClient', function (Container $container) {
    $apiHost = $container->get('config.plugin.api.host');
    return new PlacementsApiClient($apiHost);
});

$container->set('QuizAd\\Service\\Placements\\PlacementsService', function (Container $container) {
    return new PlacementsService(
        $container->get('QuizAd\\Repository\\CredentialsRepository'),
        $container->get('QuizAd\\Repository\\PlacementsRepository'),
        $container->get('QuizAd\\Repository\\WebsiteRepository'),
        $container->get('QuizAd\\Service\\Placements\\PlacementsApiClient'),
        $OAuth2TokenService = $container->get('QuizAd\\Service\\OAuth2\\OAuth2Service'),
        $container->get('QuizAd\\Service\\Registration\\IpProvider'),
        $container->get('config.plugin.scope')
    );
});


$container->set('QuizAd\\Service\\Statistics\\StatisticsApiClient', function (Container $container) {
    $apiHost = $container->get('config.plugin.api.host');
    return new StatisticsApiClient($apiHost);
});

$container->set('QuizAd\\Service\\Statistics\\StatisticsService', function (Container $container) {
    return new StatisticsService(
        $container->get('QuizAd\\Repository\\CredentialsRepository'),
        $OAuth2TokenService = $container->get('QuizAd\\Service\\OAuth2\\OAuth2Service'),
        $container->get('QuizAd\\Service\\Statistics\\StatisticsApiClient')
    );
});

$container->set('QuizAd\\Service\\Debug\\DebugApiClient', function (Container $container) {
    $apiHost = $container->get('config.plugin.api.host');
    return new DebugApiClient($apiHost);
});

$container->set('QuizAd\\Service\\Debug\\DebugService', function (Container $container) {
    return new DebugService(
        $container->get('QuizAd\\Repository\\CredentialsRepository'),
        $container->get('QuizAd\\Repository\\WebsiteRepository'),
        $container->get('QuizAd\\Repository\\PlacementsRepository'),
        $container->get('QuizAd\\Repository\\PlacementsPositionsRepository'),
        $container->get('QuizAd\\Service\\Registration\\IpProvider'),
        $container->get('config.plugin.scope'),
        $container->get('config.plugin.api.version'),
        $container->get('QuizAd\\Service\\Debug\\DebugApiClient')
    );
});

$container->set('QuizAd\\Controller\\Rest\\DebugRestController', function (Container $container) {
    $debugService = $container->get('QuizAd\\Service\\Debug\\DebugService');
    return new DebugRestController($debugService);
});

$container->set('QuizAd\\Repository\\CredentialsRepository', function (Container $container) {
    global $wpdb;

    return new CredentialsRepository($wpdb);
});
$container->set('QuizAd\\Repository\\WebsiteRepository', function (Container $container) {
    global $wpdb;

    return new WebsiteRepository($wpdb);
});
$container->set('QuizAd\\Repository\\PlacementsRepository', function (Container $container) {
    global $wpdb;

    return new PlacementsRepository($wpdb);
});
$container->set('QuizAd\\Repository\\PlacementsPositionsRepository', function (Container $container) {
    global $wpdb;

    return new PlacementsPositionsRepository($wpdb);
});

$container->set('QuizAd\\Service\\Wordpress\\PageService', function (Container $container) {
    $placementService  = $container->get('QuizAd\\Service\\Placements\\PlacementsService');
    $websiteHeaderCode = $container->get('QuizAd\\Service\\Placements\\HeaderCodeApiService');
    return new PageService($placementService, $websiteHeaderCode);
});