<?php

/*
@link              https://www.quizad.pl
@since             1.0.0
@package           QuizAd

@wordpress-plugin
Plugin Name: QuizAd
Plugin URI: https://www.quizad.pl
Description: This plugin allows QuizAd advertisement platform to manage quiz text links on wordpress website.
Version: 1.5.4
Author: https://www.quizad.pl
License: LGPL v3.0
License URL: http://www.gnu.org/licenses/lgpl-3.0.html
Since: 1.2.0
Text Domain: ad-quiz
Domain Path:       /languages
*/

require_once __DIR__ . '/Autoloader.php';

use QuizAd\Autoloader;
use QuizAd\Assets\Loaders\AdminAssetsLoader;
use QuizAd\Container\Container;
use QuizAd\Controller\Rest\AccessTokenRestController;
use QuizAd\Controller\Rest\DebugRestController;
use QuizAd\Controller\Rest\LoginRestController;
use QuizAd\Controller\Rest\PlacementQuizRestController;
use QuizAd\Controller\Rest\DefaultPlacementRestController;
use QuizAd\Controller\Rest\PlacementsExcludeRestController;
use QuizAd\Controller\Rest\PlacementsUpdateRestController;
use QuizAd\Controller\Rest\RegistrationRestController;
use QuizAd\Controller\Rest\PlacementLocationsRestController;
use QuizAd\Controller\Rest\ReinstallRestController;
use QuizAd\Controller\Rest\RemoveRestController;
use QuizAd\Controller\Rest\ResentEmailRestController;
use QuizAd\Controller\Rest\SearchRestController;
use QuizAd\Controller\Rest\StatisticsRestController;
use QuizAd\Controller\View\DashViewController;
use QuizAd\Controller\View\PlacementsViewController;
use QuizAd\Database\CredentialsRepository;
use QuizAd\Database\PlacementsPositionsRepository;
use QuizAd\Database\PlacementsRepository;
use QuizAd\Database\WebsiteRepository;
use QuizAd\Service\Placements\HeaderCodeApiService;
use QuizAd\Service\Placements\DisplayPlacementsService;
use QuizAd\Service\Wordpress\PageService;

try
{
    Autoloader::register();
    // autoloader may throw exception, so handle it

    $adminLoader = new AdminAssetsLoader();
    $adminLoader->init();

    $container = new Container();

    include __DIR__ . '/config/dependencies.php';

    // on plugin activation event create DB structures
    register_activation_hook(__FILE__, function () use ($container) {

        /** @var CredentialsRepository $credentialsRepository */
        $credentialsRepository = $container->get('QuizAd\\Repository\\CredentialsRepository');
        $credentialsRepository->createTable();
        /** @var WebsiteRepository $websiteRepository */
        $websiteRepository = $container->get('QuizAd\\Repository\\WebsiteRepository');
        $websiteRepository->createTable();
        /** @var PlacementsRepository $placementRepository */
        $placementRepository = $container->get('QuizAd\\Repository\\PlacementsRepository');
        $placementRepository->createTable();
        /** @var PlacementsPositionsRepository $placementPositionRepository */
        $placementPositionRepository = $container->get('QuizAd\\Repository\\PlacementsPositionsRepository');
        $placementPositionRepository->createTable();
    });

    add_action('admin_menu', function () use ($container) {

        add_menu_page(
            'QuizAd', 'QuizAd', 'manage_options',
            'QuizAd', '', '', 75
        );

        add_submenu_page(
            'QuizAd', 'Ustawienia', 'Ustawienia',
            'manage_options', 'my-submenu-ustawienia',
            function () use ($container) {

                /** @var DashViewController $dashViewController */
                $dashViewController = $container->get("QuizAd\\Controller\\View\\DashViewController");
                $dashViewController->renderTemplate();
            }
        );
        add_submenu_page(
            'QuizAd', 'Zaawansowane', 'Zaawansowane',
            'manage_options', 'my-submenu-zaawansowane',
            function () use ($container) {

                /** @var PlacementsViewController $placementsViewController */
                $placementsViewController = $container->get("QuizAd\\Controller\\View\\PlacementsViewController");
                $placementsViewController->renderTemplate();
            }
        );
//        add_submenu_page(
//            'QuizAd', 'Statystyki', 'Statystyki',
//            'manage_options', 'my-submenu-statystyki',
//            function () use ($container) {
//
//                /** @var StatisticsViewController $statisticsViewController */
//                $statisticsViewController = $container->get("QuizAd\\Controller\\View\\StatisticsViewController");
//                $statisticsViewController->renderTemplate();
//            }
//        );
        remove_submenu_page('QuizAd', 'QuizAd');

    });

//    /**
//     * Here we inject init section (which is a string containing HTML source code) on user's page.
//     */
//    add_action('wp_head', function () use ($container) {
//        /** @var HeaderCodeApiService $websiteHeaderCode */
//        $websiteHeaderCode = $container->get('QuizAd\\Service\\Placements\\HeaderCodeApiService');
//        echo $websiteHeaderCode->getWebsiteWithHeaderCode()->getHeaderCode();
//    });


    add_action("wp_ajax_quizAd_register", function () use ($container) {
        /** @var RegistrationRestController $settingsRestController */
        $settingsRestController = $container->get('QuizAd\\Controller\\Rest\\RegistrationRestController');
        /** sanitization field inside controller handle */
        $settingsRestController->handleRequest(array_merge([], $_POST));
    });
    add_action("wp_ajax_quizAd_login", function () use ($container) {
        /** @var LoginRestController $loginRestController */
        $loginRestController = $container->get('QuizAd\\Controller\\Rest\\LoginRestController');
        $loginRestController->handleRequest(array_merge([], $_POST));
    });
    add_action("wp_ajax_quizAd_access_token", function () use ($container) {
        /** @var AccessTokenRestController $tokenRestController */
        $tokenRestController = $container->get('QuizAd\\Controller\\Rest\\AccessTokenRestController');
        $tokenRestController->handleRequest(array_merge([], $_POST));
    });
    add_action("wp_ajax_quizAd_email_resent", function () use ($container) {
        /** @var ResentEmailRestController $resentEmailRestController */
        $resentEmailRestController = $container->get('QuizAd\\Controller\\Rest\\ResentEmailRestController');
        $resentEmailRestController->handleRequest(array_merge([], $_POST));
    });
    add_action("wp_ajax_quizAd_placements_position", function () use ($container) {
        /** @var PlacementLocationsRestController $displayPlacementsController */
        $displayPlacementsController = $container->get('QuizAd\\Controller\\Rest\\PlacementLocationsRestController');
        $displayPlacementsController->handleRequest(array_merge([], $_POST));
    });
    add_action("wp_ajax_quizAd_placements_sentence", function () use ($container) {
        /** @var PlacementQuizRestController $quizPlacementsController */
        $quizPlacementsController = $container->get('QuizAd\\Controller\\Rest\\PlacementQuizRestController');
        $quizPlacementsController->handleRequest(array_merge([], $_POST));
    });
    add_action("wp_ajax_quizAd_placements_list", function () use ($container) {
        /** @var DefaultPlacementRestController $placementsListController */
        $placementsListController = $container->get('QuizAd\\Controller\\Rest\\DefaultPlacementRestController');
        $placementsListController->handleRequest(array_merge([], $_POST));
    });
    add_action("wp_ajax_quizAd_statistics_data", function () use ($container) {
        /** @var StatisticsRestController $statisticsRestController */
        $statisticsRestController = $container->get('QuizAd\\Controller\\Rest\\StatisticsRestController');
        $statisticsRestController->handleRequest(array_merge([], $_POST));
    });
    add_action("wp_ajax_quizAd_placements_download", function () use ($container) {
        /** @var PlacementsUpdateRestController $placementsUpdateRestController */
        $placementsUpdateRestController = $container->get('QuizAd\\Controller\\Rest\\PlacementsUpdateRestController');
        $placementsUpdateRestController->handleRequest(array_merge([], $_POST));
    });
    add_action("wp_ajax_quizAd_placements_exclude", function () use ($container) {
        /** @var PlacementsExcludeRestController $placementsExcludeRestController */
        $placementsExcludeRestController = $container->get('QuizAd\\Controller\\Rest\\PlacementsExcludeRestController');
        $placementsExcludeRestController->handleRequest(array_merge([], $_POST));
    });
    add_action("wp_ajax_quizAd_get_post", function () use ($container) {
        /** @var SearchRestController $searchRestController */
        $searchRestController = $container->get('QuizAd\\Controller\\Rest\\SearchRestController');
        $searchRestController->handleRequest(array_merge([], $_POST));
    });
    add_action("wp_ajax_quizAd_debug", function () use ($container) {
        /** @var DebugRestController $debugRestController */
        $debugRestController = $container->get('QuizAd\\Controller\\Rest\\DebugRestController');
        $debugRestController->handleRequest(array_merge(['type' => 'manual'], $_POST));
    });

    add_action("wp_ajax_quizAd_deactivate", function () use ($container) {
        deactivate_plugins('/QuizAd/QuizAd.php');
        wp_redirect(admin_url('/plugins.php'));
        die();
    });
    add_action("wp_ajax_quizAd_reinstall", function () use ($container) {
        deactivate_plugins('/QuizAd/QuizAd.php');

        /** @var ReinstallRestController $reinstallRestController */
        $reinstallRestController = $container->get('QuizAd\\Controller\\Rest\\ReinstallRestController');
        $reinstallRestController->handleRequest(array_merge([], $_POST));
        wp_redirect(admin_url('/plugins.php'));
        die();
    });
    add_action("wp_ajax_quizAd_delete", function () use ($container) {
        /** @var RemoveRestController $removeRestController */
        $removeRestController = $container->get('QuizAd\\Controller\\Rest\\RemoveRestController');
        $removeRestController->handleRequest(array_merge([], $_POST));
    });

    /**
     * Display ads on categories and exclude on tags.
     */
    /** @var DisplayPlacementsService $displayPlacementsService */
    $displayPlacementsService = $container->get('QuizAd\\Service\\Placements\\DisplayPlacementsService');
    $checkedPositions         = explode(',', $displayPlacementsService->getCurrentWebsite()->getDisplayPositions());
    $excludedPositions        = explode(',', $displayPlacementsService->getCurrentWebsite()->getExcludePosition());
    /** @var PageService $pageService */
    $pageService = $container->get('QuizAd\\Service\\Wordpress\\PageService');
    $pageService->addPlacementsToCurrentPage($checkedPositions, $excludedPositions);

}
catch (Exception $exception)
{
    // do nothing
}