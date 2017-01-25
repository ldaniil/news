<?php

/* @var $news \app\models\NewsModel */

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use dosamigos\ckeditor\CKEditor;

?>

<h2 style="margin:0px 0px 20px;"><?php if ($news->isNewRecord): ?>Добавить новость<?php else: ?>Редактировать новость<?php endif; ?></h2>

<?php

$form = ActiveForm::begin(['id' => 'form-news', 'options' => ['class' => 'form-50']]);

echo '<div class="row">';

    echo '<div class="col-md-6">';

        echo $form->field($news, 'title');

    echo '</div>';

    echo '<div class="col-md-12">';

        echo $form
            ->field($news, 'content')
            ->widget(CKEditor::className(), [
                'preset' => 'basic',
            ]);

    echo '</div>';

echo '</div>';

echo Html::submitButton('Сохранить', ['class' => 'btn btn-success']);

ActiveForm::end();