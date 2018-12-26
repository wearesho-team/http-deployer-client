<?php

declare(strict_types=1);

namespace Wearesho\Deployer\Controllers\Login\Form\Validator;

use Sonata\GoogleAuthenticator;
use Wearesho\Deployer;
use yii\di;

/**
 * Class Authenticator
 * @package Wearesho\Deployer\Controllers\Login\Form\Validator
 */
class Authenticator extends Deployer\Models\User\Validator
{
    /** @var string */
    public $message = 'Неверный код Google Authenticator';

    /** @var array|string|GoogleAuthenticator\GoogleAuthenticatorInterface */
    public $authenticator = [
        'class' => GoogleAuthenticator\GoogleAuthenticator::class,
    ];

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init(): void
    {
        parent::init();
        $this->authenticator = di\Instance::ensure(
            $this->authenticator,
            GoogleAuthenticator\GoogleAuthenticatorInterface::class
        );
    }

    /**
     * @param mixed $value
     * @return array|null
     * @throws \yii\base\InvalidConfigException
     */
    protected function validateValue($value): ?array
    {
        $user = $this->validateUser();
        if ($this->authenticator->checkCode($user->authenticator_secret, $value)) {
            return null;
        }
        return [$this->message,];
    }
}
