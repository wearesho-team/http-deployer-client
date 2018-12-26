<?php

use Wearesho\Deployer;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var Deployer\Models\Project\Server $model */

$this->title = "Добавление сервера {$model->project->name}";


$form = ActiveForm::begin([
    'id' => 'server-create-form',
    'method' => 'post',
    'action' => '/server/create?project=' . htmlspecialchars($model->project->name),
]) ?>
<?= $form->field($model, 'host') ?>
<?= $form->field($model, 'secret') ?>

<div class="form-group">
    <div class="col-lg-offset-1 col-lg-11">
        <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary']) ?>
    </div>
</div>
<?php ActiveForm::end() ?>

