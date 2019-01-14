<?php

use Dotenv\Dotenv;

$dotEnv = new Dotenv(dirname(dirname(__DIR__)));
$dotEnv->load();
