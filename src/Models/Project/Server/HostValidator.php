<?php

namespace Wearesho\Deployer\Models\Project\Server;

use Wearesho\Deployer;
use yii\base;
use yii\di;
use yii\validators;

/**
 * Class HostValidator
 * @package Wearesho\Deployer\Models\Project\Server
 */
class HostValidator extends validators\Validator
{
    /** @var array|string|Deployer\Repositories\Environment */
    public $repository = [
        'class' => Deployer\Repositories\Environment::class,
    ];

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init(): void
    {
        parent::init();
        $this->repository = di\Instance::ensure(
            $this->repository,
            Deployer\Repositories\Environment::class
        );
    }

    /**
     * @param base\Model $model
     * @param string $attribute
     * @throws base\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function validateAttribute($model, $attribute)
    {
        if (!$model instanceof Deployer\Models\Project\Server) {
            throw new base\InvalidConfigException(
                static::class . " can be only append to " . Deployer\Models\Project\Server::class
            );
        }

        try {
            $this->repository->test($model->project->name, $model);
        } catch (Deployer\Repositories\Environment\Exception\InvalidSecret $e) {
            $model->addError('secret', $e->getMessage());
        } catch (\Throwable $e) {
            $model->addError('host', $e->getMessage());
        }
    }
}
