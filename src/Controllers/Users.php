<?php


namespace Wearesho\Deployer\Controllers;

use yii\web;
use yii\di;
use yii\filters;
use Wearesho\Deployer;

/**
 * Class Users
 * @package Wearesho\Deployer\Controllers
 */
class Users extends web\Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => filters\AccessControl::class,
                'rules' => [
                    [
                        'class' => filters\AccessRule::class,
                        'roles' => ['@',],
                        'allow' => true,
                    ],
                ],
            ],
        ];
    }

    public function actionIndex(): string
    {
        return "Hello, User!";
    }
}
