<?php

namespace app\modules\backend\controllers;

use yii;
use app\modules\backend\components\Controller;
use app\models\search\NewsSearch;
use app\models\NewsModel;

class NewsController extends Controller
{
	public function actionIndex()
	{
		$newsSearch = new NewsSearch;
		$newsSearch->load(Yii::$app->request->get());

		return $this->render('index', ['newsSearch' => $newsSearch]);
	}

	public function actionSave()
	{
		$id = Yii::$app->request->get('id');

		if ($id) {
			$news = NewsModel::findOne($id);
		}

		if (empty($news)) {
			$news = new NewsModel;
		}

		if ($news->load(Yii::$app->request->post()) && $news->save())
		{
			$this->redirect('index');
		}

		return $this->render('save', ['news' => $news]);
	}
}