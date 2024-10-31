<?php

/** @var Container $container */

use QuizAd\Container\Container;

$container->set('config.plugin.api.host', function () {
    return 'https://plugin.splashandroll.com';
});

$container->set('config.plugin.api.recaptcha.token', function () {
    return '6Ld2Z6QUAAAAAMADoAFI6jhBgpsTbeDwCFPXsFmU';
});

$container->set('config.plugin.api.version', function () {
    return '2.0.0';
});

$container->set('config.plugin.api.recaptcha.url', function () {
    return 'https://www.recaptcha.net/recaptcha/api.js';
});

$container->set('config.plugin.scope', function () {
    return 'quiz_plugins_api';
});