<?php

use Wearesho\Deployer;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var Deployer\Controllers\Environment\Form $model */
/** @var Deployer\Models\Project $project */

$this->title = $model->key ? "Редактирование {$model->key}" : "Создание";


$form = ActiveForm::begin([
    'id' => 'environment-form',
    'method' => 'post',
    'action' => '/environment/put?' . http_build_query(['project' => $project->name, 'key' => $model->key]),
]) ?>
<?= $form->field($model, 'key')->textInput(['disabled' => (bool)$model->key,]) ?>
<?= $form->field($model, 'value') ?>

<div class="form-group">
    <div class="col-lg-offset-1 col-lg-11">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
    </div>
</div>
<?php ActiveForm::end() ?>
