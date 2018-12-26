<?php

use Wearesho\Deployer;
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
                История проекта
                <?= htmlspecialchars($project->name) ?>
            </h4>
        </div>
        <div class="panel-body">
            <?= /** @noinspection PhpUnhandledExceptionInspection */
            grid\GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    'id',
                    'user' => [
                        'attribute' => 'user',
                        'value' => function (Deployer\Models\Project\History $model): string {
                            return htmlspecialchars($model->user->login);
                        },
                    ],
                    'key',
                    'value',
                    'created_at',
                ],
            ]); ?>
        </div>
    </div>
</section>
