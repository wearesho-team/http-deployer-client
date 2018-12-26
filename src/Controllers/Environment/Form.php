<?php

namespace Wearesho\Deployer\Controllers\Environment;

use Wearesho\Deployer;
use yii\base;

/**
 * Class Form
 * @package Wearesho\Deployer\Controllers\Environment
 */
class Form extends base\Model
{
    /** @var string */
    public $key;

    /** @var string */
    public $value;

    /** @var array|string|Deployer\Repositories\Environment */
    public $repository = [
        Deployer\Repositories\Environment::class,
    ];

    public function rules(): array
    {
        return [
            [['key', 'value',], 'required',],
            [['key', 'value',], 'string',],
        ];
    }
}
