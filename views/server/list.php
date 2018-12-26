<?php

use Wearesho\Deployer;
use yii\helpers\Html;
use yii\grid;

/**
 * @var \yii\data\ArrayDataProvider $dataProvider
 * @var Deployer\Models\Project $project
 */

?>
<section class="content">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>
                Серверы проекта
                <?= htmlspecialchars($project->name) ?>
                <a
                        href="/server/create?project=<?= htmlspecialchars($project->name) ?>"
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
                    'host',
                    'secret',
                    'actions' => [
                        'class' => grid\ActionColumn::class,
                        'template' => '{delete}',
                        'buttons' => [
                            'delete' => function ($url, Deployer\Models\Project\Server $server): string {
                                return Html::a(
                                    'Удалить',
                                    "/server/delete?id=" . urlencode($server->id)
                                );
                            },
                        ],
                    ],
                ],
            ]); ?>
        </div>
    </div>
</section>
