<?php

namespace Wearesho\Deployer\Controllers\Login;

use Horat1us\Yii\Validators\LoaderValidator;
use Wearesho\Deployer;
use yii\base;

/**
 * Class Form
 * @package Wearesho\Deployer\Controllers\Login
 */
class Form extends base\Model
{
    /** @var string */
    public $login;

    /** @var string */
    public $secure;

    /** @var string */
    public $password;

    /** @var Deployer\Models\User */
    protected $user;

    public function rules(): array
    {
        return [
            [['login', 'secure', 'password',], 'required',],
            [
                ['login',],
                LoaderValidator::class,
                'targetClass' => Deployer\Models\User::class,
                'targetAttribute' => 'login',
                'attribute' => 'user',
            ],
            [
                ['secure',],
                Form\Validator\Authenticator::class,
                'user' => function (): Deployer\Models\User {
                    return $this->user;
                },
                'when' => function (): bool {
                    return !$this->hasErrors('login');
                },
            ],
            [
                ['password',],
                Form\Validator\Password::class,
                'user' => function (): Deployer\Models\User {
                    return $this->user;
                },
                'when' => function (): bool {
                    return !$this->hasErrors('login');
                },
            ],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'login' => 'Логин',
            'secure' => 'Google Authenticator',
            'passowrd' => 'Пароль',
        ];
    }

    /**
     * @return Deployer\Models\User
     */
    public function getUser(): Deployer\Models\User
    {
        return $this->user;
    }
}
