<?php

use Wearesho\Deployer;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var Deployer\Controllers\Login\Form $model */

$this->title = "Вход";


$form = ActiveForm::begin([
    'id' => 'login-form',
    'method' => 'post',
    'action' => '/login',
]) ?>
<?= $form->field($model, 'login') ?>
<?= $form->field($model, 'secure') ?>
<?= $form->field($model, 'password')->passwordInput() ?>

<div class="form-group">
    <div class="col-lg-offset-1 col-lg-11">
        <?= Html::submitButton('Вход', ['class' => 'btn btn-primary']) ?>
    </div>
</div>
<?php ActiveForm::end() ?>

