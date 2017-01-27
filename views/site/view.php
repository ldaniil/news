<?php

/* @var $news app\models\NewsModel */

$this->title = 'Новости';
$this->params['breadcrumbs'][] = $news->title;

?>

<div class="site-about">
    <h1><?= $news->title ?></h1>
    <?= $news->content ?>
</div>
