<?php

namespace Wearesho\Deployer\Controllers;

use Wearesho\Deployer;
use yii\data\ArrayDataProvider;
use yii\web;
use yii\filters;

/**
 * Class History
 * @package Wearesho\Deployer\Controllers
 */
class History extends web\Controller
{
    public $defaultAction = 'list';

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
        $history = $project->getHistory()
            ->joinWith('user')
            ->all();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $history,
            'modelClass' => Deployer\Models\Project\History::class,
        ]);

        return $this->render('list', compact("dataProvider", "project"));
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
