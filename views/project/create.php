<?php

use Wearesho\Deployer;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var Deployer\Models\Project $model */

$this->title = "Добавление проекта";


$form = ActiveForm::begin([
    'id' => 'project-create-form',
    'method' => 'post',
    'action' => '/project/create',
]) ?>
<?= $form->field($model, 'name') ?>

<div class="form-group">
    <div class="col-lg-offset-1 col-lg-11">
        <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary']) ?>
    </div>
</div>
<?php ActiveForm::end() ?>

