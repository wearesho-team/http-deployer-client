<?php

use Dotenv\Dotenv;

$dotEnv = new Dotenv(dirname(dirname(__DIR__)));
$dotEnv->load();

\Yii::setAlias(
    '@Wearesho/Deployer',
    dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'src'
);
\Yii::setAlias('@runtime', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Runtime');
