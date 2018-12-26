<?php

require(dirname(__DIR__) . '/src/Config/autoload.php');

use Wearesho\Deployer;
use yii\helpers\ArrayHelper;
use yii\web;

$commonConfig = require dirname(__DIR__) . '/src/Config/config.php';
$config = [
    'defaultRoute' => 'users',
    'controllerMap' => [
        'users' => [
            'class' => Deployer\Controllers\Users::class,
        ],
        'login' => [
            'class' => Deployer\Controllers\Login::class,
        ]
    ],
    'components' => [
        'request' => [
            'class' => web\Request::class,
            'enableCookieValidation' => true,
            'enableCsrfValidation' => true,
            'cookieValidationKey' => getenv('COOKIE_VALIDATION_KEY'),
        ],
        'response' => [
            'class' => web\Response::class,
        ],
        'user' => [
            'class' => web\User::class,
            'identityClass' => Deployer\Models\User::class,
            'enableAutoLogin' => true,
            'enableSession' => true,
            'loginUrl' => ['/login'],
        ],
        'urlManager' => [
            'class' => web\UrlManager::class,
            'showScriptName' => false,
            'enablePrettyUrl' => true,
        ],
        'assetManager' => [
            'bundles' => [
                web\JqueryAsset::class => [
                    'sourcePath' => null,   // do not publish the bundle
                ],
            ],
        ]
    ],
];

/** @noinspection PhpUnhandledExceptionInspection */
$application = new web\Application(ArrayHelper::merge($commonConfig, $config));
$application->run();
