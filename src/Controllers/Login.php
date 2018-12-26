<?php

namespace Wearesho\Deployer\Controllers;

use yii\filters;
use yii\web;
use yii\di;
use Wearesho\Deployer;

/**
 * Class Login
 * @package Wearesho\Deployer\Controllers
 */
class Login extends web\Controller
{
    /** @var string|array|web\Request */
    public $request = 'request';

    /** @var string|array|web\User */
    public $user = 'user';

    /** @var array|string|Login\Form */
    public $form = [
        'class' => Login\Form::class,
    ];

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init(): void
    {
        parent::init();
        $this->request = di\Instance::ensure($this->request, web\Request::class);
        $this->form = di\Instance::ensure($this->form, Login\Form::class);
        $this->user = di\Instance::ensure($this->user, web\User::class);
    }

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => filters\AccessControl::class,
                'rules' => [
                    [
                        'class' => filters\AccessRule::class,
                        'allow' => true,
                        'roles' => ['?',],
                        'actions' => ['index',],
                    ],
                    [
                        'class' => filters\AccessRule::class,
                        'allow' => true,
                        'roles' => ['@',],
                        'actions' => ['logout',],
                    ]
                ],
            ],
        ];
    }

    /**
     * @return string|web\Response
     * @throws web\HttpException
     */
    public function actionIndex()
    {
        switch ($this->request->method) {
            case 'POST':
                return $this->login();
            case 'GET':
                return $this->form();
            default:
                throw new web\MethodNotAllowedHttpException();
        }
    }

    public function actionLogout()
    {
        $this->user->logout(true);
        return $this->redirect(['login/index']);
    }

    /**
     * @return string|web\Response
     * @throws web\ServerErrorHttpException
     */
    protected function login()
    {
        if (!$this->form->load($this->request->bodyParams) || !$this->form->validate()) {
            return $this->form();
        }
        if (!$this->user->login($this->form->getUser())) {
            throw new web\ServerErrorHttpException("Can not log in");
        }
        return $this->redirect(['/']);
    }

    protected function form(): string
    {
        return $this->render('view', ['model' => $this->form]);
    }
}
