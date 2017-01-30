<?php

/* @var $newsSearch \app\models\search\NewsSearch */

use yii\widgets\ListView;

echo ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView'	   => '_news',
	'summary'	   => '',
]);