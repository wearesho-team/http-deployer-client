<?php

namespace Wearesho\Deployer\Console\Controllers;

use Horat1us\Yii\Exceptions\ModelException;
use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use Sonata\GoogleAuthenticator\GoogleAuthenticatorInterface;
use Wearesho\Deployer;
use yii\console;
use yii\helpers;
use yii\di;

/**
 * Class UserController
 * @package Wearesho\Deployer\Console\Controllers
 */
class Users extends console\Controller
{
    /** @var array|string|GoogleAuthenticatorInterface */
    public $authenticator = [
        'class' => GoogleAuthenticator::class,
    ];

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init(): void
    {
        parent::init();
        $this->authenticator = di\Instance::ensure(
            $this->authenticator,
            GoogleAuthenticatorInterface::class
        );
    }

    /**
     * @throws \Horat1us\Yii\Interfaces\ModelExceptionInterface
     */
    public function actionCreate(): void
    {
        $user = new Deployer\Models\User;
        $this->prompt("Enter login: ", [
            'validator' => function (string $value, &$error) use ($user): bool {
                $user->login = $value;
                if (!$user->validate(['login'])) {
                    $error = $user->getFirstError('login');
                    return false;
                }
                return true;
            },
        ]);
        $password = $this->prompt("Enter password: ");
        $user->password = $password;
        $user->authenticator_secret = $this->authenticator->generateSecret();
        ModelException::saveOrThrow($user);
        $this->stdout("User {$user->id} successfuly created\n", helpers\Console::FG_GREEN);
        $this->displayLink($user);
    }

    public function actionLink(string $login): void
    {
        $user = Deployer\Models\User::findOne(['login' => $login]);
        if (!$user instanceof Deployer\Models\User) {
            $this->stderr("User {$login} not found", helpers\Console::FG_RED);
            return;
        }
        $this->displayLink($user);
    }

    public function actionDelete(string $login): void
    {
        $user = Deployer\Models\User::findOne(['login' => $login]);
        if (!$user instanceof Deployer\Models\User) {
            $this->stderr("User {$login} not found", helpers\Console::FG_RED);
            return;
        }
        $user->delete();
    }

    public function actionCode(string $login): void
    {
        $user = Deployer\Models\User::findOne(['login' => $login]);
        if (!$user instanceof Deployer\Models\User) {
            $this->stderr("User {$login} not found", helpers\Console::FG_RED);
            return;
        }
        $code = $this->authenticator->getCode($user->authenticator_secret);
        $this->stdout("Code: {$code}\n", helpers\Console::FG_GREEN);
    }

    protected function displayLink(Deployer\Models\User $user): void
    {
        $link = $this->authenticator->getUrl(
            $user->login,
            'deployer',
            $user->authenticator_secret
        );
        $this->stdout("Authenticator Link: {$link}\n", helpers\Console::FG_GREY);
    }
}
