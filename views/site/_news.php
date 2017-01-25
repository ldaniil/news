<?php

/* @var $model \app\models\NewsModel */

?>

<div class="row">
	<h2><?= $model->title ?></h2>
	<h6><?= Yii::$app->formatter->asDate($model->created_at) ?></h6>
	<?= $model->preview ?>
</div>