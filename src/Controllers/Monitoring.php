<?php

declare(strict_types=1);

namespace Wearesho\Deployer\Controllers;

use GuzzleHttp\Exception\GuzzleException;
use yii\di;
use yii\web;
use yii\base;
use Wearesho\Deployer;

/**
 * Class Monitoring
 * @package Wearesho\Deployer\Controllers
 */
class Monitoring extends web\Controller
{
    public $defaultAction = 'list';

    /** @var string|array|Deployer\Repositories\Container */
    public $repository = Deployer\Repositories\Container::class;

    /**
     * @throws base\InvalidConfigException
     */
    public function init(): void
    {
        parent::init();
        $this->repository = di\Instance::ensure(
            $this->repository,
            Deployer\Repositories\Container::class
        );
    }

    /**
     * @return string
     * @throws web\HttpException
     */
    public function actionList(): string
    {
        $projects = Deployer\Models\Project::find()
            ->innerJoinWith('servers')
            ->all();

        try {
            $data = array_map(function (Deployer\Models\Project $project): array {
                return [
                    'project' => $project->name,
                    'servers' => array_map(function (Deployer\Models\Project\Server $server): array {
                        return [
                            'hostName' => $server->hostName,
                            'containers' => $this->repository->fetch($server),
                        ];
                    }, $project->servers),
                ];
            }, $projects);
        } catch (GuzzleException $exception) {
            if (YII_DEBUG) {
                /** @noinspection PhpUnhandledExceptionInspection */
                throw $exception;
            }
            throw new web\HttpException(
                503,
                "Ошибка получения контейнеров",
                0,
                $exception
            );
        }

        return $this->render('list', compact('data'));
    }
}
