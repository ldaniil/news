<?php

namespace app\modules\backend;

use yii;

class Module extends \yii\base\Module
{
	public $layout = 'main';

	/**
	 * Инициализация админки
	 */
	public function init()
	{
		parent::init();

		Yii::configure($this, require(__DIR__ . '/config.php'));

		Yii::$app->setHomeUrl('/administration');
	}

	/**
	 * @param \yii\base\Action $action
	 *
	 * @return bool
	 */
	public function beforeAction($action)
	{
		if (!parent::beforeAction($action)) {
			return false;
		}

		// Админка доступна только администратору
		if (!Yii::$app->user->isAdministrator && $action->id != 'login') {
			Yii::$app->response->redirect('/administration/login');
		}

		return true;
	}
}