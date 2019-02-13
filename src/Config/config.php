<?php

use yii\helpers\ArrayHelper;
use yii\db\Connection;

$localConfig = __DIR__ . DIRECTORY_SEPARATOR . 'config-local.php';
$host = getenv('DB_HOST');
$name = getenv("DB_NAME");
$port = getenv("DB_PORT");
$dsn = "pgsql:host={$host};dbname={$name};port={$port}";

$logTargets = [
    [
        'class' => \yii\log\DbTarget::class,
    ],
];

if (getenv("RAVEN_DSN")) {
    $logTargets[] = [
        'class' => \notamedia\sentry\SentryTarget::class,
        'dsn' => getenv("RAVEN_DSN"),
        'except' => [
            yii\web\HttpException::class,
        ],
        'levels' => ['error', 'warning',],
        'context' => true,
    ];
}

$config = [
    'id' => 'deployer',
    'name' => 'HttpDeployer',
    'sourceLanguage' => 'ru',
    'language' => 'ru',
    'basePath' => dirname(dirname(__DIR__)),
    'components' => [
        'db' => [
            'class' => Connection::class,
            'dsn' => $dsn,
            'username' => getenv("DB_USERNAME"),
            'password' => getenv("DB_PASSWORD") ?: null,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => $logTargets,
        ],
        'cache' => [
            'class' => \yii\caching\DbCache::class,
        ],
    ],
];

return ArrayHelper::merge(
    $config,
    is_file($localConfig) ? require $localConfig : []
);
