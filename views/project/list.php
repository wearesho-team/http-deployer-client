<?php

use Wearesho\Deployer;
use yii\helpers\Html;
use yii\grid;

/**
 * @var \yii\data\ArrayDataProvider $dataProvider
 */

$this->title = "Проекты";

?>
<section class="content">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>
                Проекты
                <a
                        href="/project/create"
                        class="btn btn-success btn-sm pull-right"
                >
                    Добавить
                </a>
            </h4>
        </div>
        <div class="panel-body">
            <?= /** @noinspection PhpUnhandledExceptionInspection */
            grid\GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    'id',
                    'name',
                    'actions' => [
                        'class' => grid\ActionColumn::class,
                        'template' => '{history} {servers} {environment}',
                        'buttons' => [
                            'history' => function ($url, Deployer\Models\Project $project): string {
                                return Html::a(
                                    'История',
                                    "/history?project=" . urlencode($project->name)
                                );
                            },
                            'servers' => function ($url, Deployer\Models\Project $project): string {
                                return Html::a(
                                    'Серверы',
                                    "/server?project=" . urlencode($project->name)
                                );
                            },
                            'environment' => function ($url, Deployer\Models\Project $project): string {
                                return Html::a(
                                    'Окружение',
                                    "/environment?project=" . urlencode($project->name)
                                );
                            },
                        ],
                    ],
                ],
            ]); ?>
        </div>
    </div>
</section>
