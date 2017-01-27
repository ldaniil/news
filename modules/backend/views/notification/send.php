<?php

/* @var $setting app\models\NotificationSettingModel */
/* @var $routeDataProvider yii\data\ArrayDataProvider  */

use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use dosamigos\ckeditor\CKEditor;

?>

<h2 style="margin:0px 0px 20px;">Отправка уведомления</h2>

<?php if ($success): ?>
    <div class="alert alert-success">Уведомление отправлено</div>
<?php endif; ?>

<?php

$form = ActiveForm::begin(['id' => 'form-news', 'options' => ['class' => 'form-50']]);

echo '<div class="row">';

    echo '<div class="col-md-5">';

        echo $form->field($setting, 'title');

        echo $form->field($setting, 'message')->widget(CKEditor::className(), [
            'preset' => 'basic',
        ]);

    echo '</div>';

    echo '<div class="col-md-7">';

    echo GridView::widget([
        'dataProvider' => $routeDataProvider,
        'summary' => '',
        'columns' => [
            [
                'attribute' => 'enable',
                'label'     => '',
                'format'    => 'raw',
                'value'     => function($data) {
                    return Html::checkbox('NotificationSettingModel[routes][' . $data['role'] . '][enable]', $data['enable']);
                },
            ],
            [
                'attribute' => 'role',
                'label'     => 'Роль',
            ],
            [
                'attribute' => 'exclude',
                'label'     => 'Исключение',
                'format'    => 'raw',
                'value'     => function($data) {
                    return Html::textarea('NotificationSettingModel[routes][' . $data['role'] . '][exclude]', implode(',', $data['exclude']), ['style' => 'width:100%;']);
                },
                'contentOptions' => ['style' => 'width:100%;'],
            ],
            [
                'attribute' => 'transports',
                'label'     => 'Способ получения',
                'format'    => 'raw',
                'value'     => function($data) use ($transports) {
                    $list = '';

                    foreach ($transports as $name) {
                        $list .= Html::checkbox(
                            'NotificationSettingModel[routes][' . $data['role'] . '][transports][' . $name . ']',
                            in_array($name, $data['transports']),
                            ['label' => $name]
                        ) . '<br />';
                    }

                    return $list;
                }
            ],
        ],
    ]);

    echo '</div>';

echo '</div>';

echo Html::submitButton('Отправить', ['class' => 'btn btn-success']);

ActiveForm::end();