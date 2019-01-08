<?php

namespace Wearesho\Deployer\Controllers;

use Wearesho\Deployer;
use yii\data\ArrayDataProvider;
use yii\web;
use yii\di;
use yii\filters;

/**
 * Class Server
 * @package Wearesho\Deployer\Controllers
 */
class Server extends web\Controller
{
    /** @var string|array|web\Request */
    public $request = 'request';

    public $defaultAction = 'list';

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init(): void
    {
        parent::init();
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
        ];
    }

    /**
     * @param string $project
     * @return string
     * @throws web\HttpException
     */
    public function actionList(string $project): string
    {
        $project = $this->findProject($project);
        $environments = $project->getServers()
            ->all();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $environments,
            'modelClass' => Deployer\Models\Project\Server::class,
        ]);

        return $this->render('list', compact("dataProvider", "project"));
    }

    /**
     * @param string $project
     * @return string|web\Response
     * @throws web\HttpException
     * @throws web\MethodNotAllowedHttpException
     */
    public function actionCreate(string $project)
    {
        $project = $this->findProject($project);
        $server = new Deployer\Models\Project\Server([
            'project' => $project,
        ]);
        switch (strtoupper($this->request->method)) {
            /** @noinspection PhpMissingBreakStatementInspection */
            case 'POST':
                if ($server->load($this->request->bodyParams) && $server->save()) {
                    return $this->redirect('/server/list?project=' . htmlspecialchars($project->name));
                }
            case 'GET':
                return $this->render('create', ['model' => $server,]);
            default:
                throw new web\MethodNotAllowedHttpException();
        }
    }

    /**
     * @param int $id
     * @return web\Response
     * @throws web\HttpException
     */
    public function actionDelete(int $id): web\Response
    {
        $server = $this->findServer($id);
        /** @noinspection PhpUnhandledExceptionInspection */
        $server->delete();
        return $this->redirect('/server?project=' . htmlspecialchars($server->project->name));
    }


    /**
     * @param int $id
     * @return Deployer\Models\Project\Server
     * @throws web\HttpException
     */
    protected function findServer(int $id): Deployer\Models\Project\Server
    {
        $server = Deployer\Models\Project\Server::findOne($id);
        if (!$server instanceof Deployer\Models\Project\Server) {
            throw new web\NotFoundHttpException("Server {$id} not found");
        }
        return $server;
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
