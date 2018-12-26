<?php


namespace Wearesho\Deployer\Controllers\Login\Form;

use yii\base;
use yii\validators;
use Wearesho\Deployer;

/**
 * Class Validator
 * @package Wearesho\Deployer\Controllers\Login\Form
 */
abstract class Validator extends validators\Validator
{
    /** @var Deployer\Models\User */
    public $user;

    /**
     * @return Deployer\Models\User
     * @throws base\InvalidConfigException
     */
    final protected function validateUser(): Deployer\Models\User
    {
        if ($this->user instanceof \Closure || is_array($this->user) && is_callable($this->user)) {
            $this->user = call_user_func($this->user);
        }
        if (!$this->user instanceof Deployer\Models\User) {
            throw new base\InvalidConfigException(
                "User should contain " . Deployer\Models\User::class
            );
        }
        return $this->user;
    }
}
