<?php

/* @var $settingSearch \app\models\search\NotificationSettingSearch */

use yii\grid\GridView;
use yii\bootstrap\Html;

echo GridView::widget([
	'caption' => '<h2>Уведомления'
		. Html::a('Добавить', Yii::$app->urlManager->createUrl('/administration/notification/create'), [
			'class' => 'btn btn-success',
			'style' => 'margin-left:10px;'
		])
		. Html::a('Отправить', Yii::$app->urlManager->createUrl('/administration/notification/send'), [
			'class' => 'btn btn-info',
			'style' => 'margin-left:10px;'
		])
		. '</h2>',
	'showHeader'   => true,
	'dataProvider' => $settingSearch->search(),
	'filterModel'  => $settingSearch,
	'columns' => [
		[
			'attribute' => 'model',
		],
		[
			'attribute' => 'event',
		],
		[
			'attribute' => 'title',
		],
		[
			'attribute' => 'message',
			'format'    => 'html',
		],
		[
			'class'          => 'yii\grid\ActionColumn',
			'header'         => '',
			'headerOptions'  => ['width' => '50'],
			'contentOptions' => ['style' => 'text-align:center;'],
			'template'       => '{update}'
		]
	]
]);