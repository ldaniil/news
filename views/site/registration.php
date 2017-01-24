<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="site-login">
    <?php if (!$registration->success): ?>
        <h1><?= Html::encode($this->title) ?></h1>

        <?php $form = ActiveForm::begin([
            'id' => 'registration-form',
            'layout' => 'horizontal',
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-7\">{error}</div>",
                'labelOptions' => ['class' => 'col-lg-2 control-label'],
            ],
        ]); ?>

            <?= $form->field($registration, 'email')->textInput(['autofocus' => true]) ?>

            <?= $form->field($registration, 'password')->passwordInput() ?>

            <?= $form->field($registration, 'confirmPassword')->passwordInput() ?>

            <?= $form->field($registration, 'fio')->textInput() ?>

            <div class="form-group">
                <div class="col-lg-offset-2 col-lg-3">
                    <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-primary col-lg-12', 'name' => 'login-button']) ?>
                </div>
            </div>

        <?php ActiveForm::end(); ?>
    <?php else: ?>
        <div class="col-lg-offset-3 col-lg-6" style="text-align:center;">
            <h4>Вы успешно зарегистрированные, для активации учетной записи на ваш электронный адрес было выслано письмо с инструкцией по активации.</h4>
        </div>
    <?php endif ?>
</div>
