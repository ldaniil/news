<?php

/* @var $news \app\models\NewsModel */

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use dosamigos\ckeditor\CKEditor;

?>

<h2 style="margin:0px 0px 20px;">Регистрация пользователя</h2>

<?php

$form = ActiveForm::begin(['id' => 'form-news', 'options' => ['class' => 'form-50']]);

echo '<div class="row">';

    echo '<div class="col-md-6">';

        echo $form->field($registration, 'email')->textInput(['autofocus' => true]);

        echo $form->field($registration, 'fio')->textInput();

    echo '</div>';

echo '</div>';

echo Html::submitButton('Зарегистрировать', ['class' => 'btn btn-success']);

ActiveForm::end();