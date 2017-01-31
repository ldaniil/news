<?php

/* @var $userSearch \app\models\search\UserSearch */

use yii\grid\GridView;
use yii\bootstrap\Html;

echo GridView::widget([
	'caption' => '<h2>Пользователи' . Html::a('Регистрация', Yii::$app->urlManager->createUrl('/administration/user/registration'), [
			'class' => 'btn btn-success',
			'style' => 'margin-left:10px;'
	]) . '</h2>',
	'showHeader'   => true,
	'dataProvider' => $userSearch->search(),
	'filterModel'  => $userSearch,
	'columns' => [
		[
			'attribute' => 'id',
			'headerOptions' => ['width' => '70px'],
		],
		[
			'attribute' => 'email',
		],
		[
			'attribute' => 'fio',
		],
		[
			'attribute' => 'status',
			'format'	=> 'raw',
			'value'		=> function($data) {
				return $data->getStatusLabel();
			},
		],
		[
			'attribute' => 'created_at',
			'format'    => 'date'
		],
	]
]);