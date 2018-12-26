<?php

namespace Wearesho\Deployer\Controllers;

use Wearesho\Deployer;
use yii\web;
use yii\di;
use yii\filters;

/**
 * Class Environment
 * @package Wearesho\Deployer\Controllers
 */
class Environment extends web\Controller
{
    public $defaultAction = 'list';

    /** @var string|array|Deployer\Repositories\Environment */
    public $repository = [
        'class' => Deployer\Repositories\Environment::class,
    ];

    /** @var string|array|web\Request */
    public $request = 'request';

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
        $this->request = di\Instance::ensure($this->request, web\Request::class);
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
                        'roles' => ['@',],
                    ],
                ],
            ],
            'verb' => [
                'class' => filters\VerbFilter::class,
                'actions' => [
                    'delete' => ['POST',],
                    'list' => ['GET',],
                    'put' => ['GET', 'POST',],
                ],
            ],
        ];
    }

    /**
     * @param string $project
     * @return string
     * @throws web\HttpException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function actionList(string $project): string
    {
        $project = $this->findProject($project);
        $environment = $this->repository->fetch($project);
        $servers = array_map(function (Deployer\Models\Project\Server $server): string {
            return $server->hostName;
        }, $project->servers);
        sort($servers);

        return $this->render('list', compact('environment', 'project', 'servers'));
    }

    /**
     * @param string $project
     * @param string $key
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws web\HttpException
     * @throws \Horat1us\Yii\Interfaces\ModelExceptionInterface
     */
    public function actionDelete(string $project, string $key): string
    {
        $project = $this->findProject($project);
        $previous = $this->repository->delete($project, $key);

        return $this->render('delete', compact('previous', 'project', 'key'));
    }

    /**
     * @param string $project
     * @param string|null $key
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Horat1us\Yii\Interfaces\ModelExceptionInterface
     * @throws web\HttpException
     * @throws web\MethodNotAllowedHttpException
     */
    public function actionPut(string $project, string $key = null): string
    {
        $project = $this->findProject($project);
        $model = new Environment\Form(['key' => $key]);
        if (!is_null($key) && count($project->servers)) {
            $model->value = $this->repository->get($project->servers[0], $key);
        }
        switch ($this->request->method) {
            case 'POST':
                if (!$model->load($this->request->bodyParams) || !$model->validate()) {
                    continue;
                }
                $previous = $this->repository->put($project, $model->key, $model->value);
                $key = $model->key;
                return $this->render('put', compact('previous', 'project', 'key'));
            case 'GET':
                return $this->render('edit', compact('model', 'project'));

        }
        throw new web\MethodNotAllowedHttpException();
    }

    /**
     * @param string $name
     * @return Deployer\Models\Project
     * @throws web\HttpException
     */
    protected function findProject(string $name): Deployer\Models\Project
    {
        $project = Deployer\Models\Project::findOne(['name' => $name]);
        if (!$project instanceof Deployer\Models\Project) {
            throw new web\NotFoundHttpException("Project {$name} not found");
        }
        return $project;
    }
}
