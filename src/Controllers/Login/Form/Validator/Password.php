<?php

declare(strict_types=1);

namespace Wearesho\Deployer\Controllers\Login\Form\Validator;

use Wearesho\Deployer;

/**
 * Class Password
 * @package Wearesho\Deployer\Controllers\Login\Form\Validator
 */
class Password extends Deployer\Controllers\Login\Form\Validator
{
    /** @var string */
    public $message = 'Неверный пароль';

    /**
     * @param mixed $value
     * @return array|null
     * @throws \yii\base\InvalidConfigException
     */
    protected function validateValue($value): ?array
    {
        $user = $this->validateUser();
        if ($user->validatePassword($value)) {
            return null;
        }
        return [$this->message,];
    }
}
