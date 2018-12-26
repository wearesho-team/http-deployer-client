<?php

namespace Wearesho\Deployer\Models\User\Validator;

use Wearesho\Deployer;

/**
 * Class Password
 * @package Wearesho\Deployer\Models\User\Validator
 */
class Password extends Deployer\Models\User\Validator
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
