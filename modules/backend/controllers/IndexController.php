<?php

namespace app\modules\backend\controllers;

use yii;
use app\modules\backend\components\Controller;
use app\modules\backend\models\LoginForm;

class IndexController extends Controller
{
	/**
	 * @inheritdoc
	 */
	public function actions()
	{
		return [
			'error' => [
				'class' => 'yii\web\ErrorAction',
			],
		];
	}

	/**
	 * Главная
	 *
	 * @return string
	 */
	public function actionIndex()
	{
		return $this->render('index');
	}

	/**
	 * Вход
	 *
	 * @return string|\yii\web\Response
	 */
	public function actionLogin()
	{
		if (!Yii::$app->user->isGuest) {
			return $this->redirect('/administration');
		}

		$model = new LoginForm();

		if ($model->load(Yii::$app->request->post()) && $model->login()) {
			return $this->redirect('/administration');
		}

		return $this->render('login', [
			'model' => $model,
		]);
	}

	/**
	 * Выход
	 *
	 * @return string
	 */
	public function actionLogout()
	{
		Yii::$app->user->logout();

		return $this->redirect('/administration/login');
	}
}