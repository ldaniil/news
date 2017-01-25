<?php

/* @var $newsSearch \app\models\search\NewsSearch */

use yii\grid\GridView;
use yii\bootstrap\Html;

echo GridView::widget([
	'caption' => '<h2>Новости' . Html::a('Добавить', Yii::$app->urlManager->createUrl('/administration/news/create'), [
			'class' => 'btn btn-success',
			'style' => 'margin-left:10px;'
	]) . '</h2>',
	'showHeader'   => true,
	'dataProvider' => $newsSearch->search(),
	'filterModel'  => $newsSearch,
	'columns' => [
		[
			'attribute' => 'id',
			'headerOptions' => ['width' => '70px'],
		],
		[
			'attribute' => 'title',
		],
		[
			'attribute' => 'preview',
			'format'    => 'html',
		],
		[
			'attribute' => 'created_at',
			'format'    => 'date'
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