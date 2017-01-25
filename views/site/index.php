<?php

/* @var $newsSearch \app\models\search\NewsSearch */

use yii\widgets\ListView;

echo ListView::widget([
    'dataProvider' => $newsSearch->search(),
    'itemView'	   => '_news',
	'summary'	   => '',
]);