<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php

    if (Yii::$app->user->isAdministrator) {
        NavBar::begin(
            [
                'brandLabel' => 'Панель управления',
                'brandUrl'   => '/administration',
                'options'    => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]
        );
        echo Nav::widget(
            [
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items'   => [
                    ['label' => 'Новости', 'url' => ['/administration/news']],
                    ['label' => 'Пользователи', 'url' => ['/administration/user']],
                    ['label' => 'Уведомления', 'url' => ['/administration/notification']],
                    (
                        '<li>'
                        . Html::beginForm(['/administration/logout'], 'post')
                        . Html::submitButton(
                            'Выход (' . Yii::$app->user->identity->username . ')',
                            ['class' => 'btn btn-link logout']
                        )
                        . Html::endForm()
                        . '</li>'
                    ),
                ],
            ]
        );
        NavBar::end();
    }

    ?>

    <div class="container">
        <?= $content ?>
    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
