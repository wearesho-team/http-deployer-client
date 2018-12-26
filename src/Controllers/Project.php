<?php

namespace Wearesho\Deployer\Controllers;

use Wearesho\Deployer;
use yii\data\ArrayDataProvider;
use yii\web;
use yii\di;
use yii\filters;

/**
 * Class Project
 * @package Wearesho\Deployer\Controllers
 */
class Project extends web\Controller
{
    /** @var string|array|web\Request */
    public $request = 'request';

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

    public function actionIndex(): string
    {
        $projects = Deployer\Models\Project::find()
            ->joinWith('servers')
            ->all();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $projects,
            'modelClass' => Deployer\Models\Project::class,
        ]);

        return $this->render('list', ['dataProvider' => $dataProvider]);
    }

    /**
     * @return string|web\Response
     * @throws web\HttpException
     */
    public function actionCreate()
    {
        $project = new Deployer\Models\Project;
        switch ($this->request->method) {
            /** @noinspection PhpMissingBreakStatementInspection */
            case 'POST':
                if ($project->load($this->request->bodyParams) && $project->save()) {
                    return $this->redirect('/project/index');
                }
            case 'GET':
                return $this->render('create', ['model' => $project,]);
            default:
                throw new web\MethodNotAllowedHttpException();
        }
    }
}
