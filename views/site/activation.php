<?php

/* @var $activation \app\models\ActivationForm */

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

?>

<h2 style="margin:0px 0px 20px;">Активация учетной записи</h2>

<?php

$form = ActiveForm::begin(['id' => 'form-news', 'options' => ['class' => 'form-50']]);

echo '<div class="row">';

echo '<div class="col-md-6">';

echo $form->field($activation, 'password')->passwordInput(['autofocus' => true]);

echo $form->field($activation, 'confirmPassword')->passwordInput();

echo '</div>';

echo '</div>';

echo Html::submitButton('Активировать', ['class' => 'btn btn-success']);

ActiveForm::end();