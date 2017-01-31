<?php

namespace app\modules\backend;

use app\models\User;
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

		Yii::$app->errorHandler->errorAction = 'administration/index/error';
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

		// Админка доступна только администратору и модератору
		if ($action->id != 'login') {
			if (Yii::$app->user->isGuest || (Yii::$app->user && !$this->hasAccess(Yii::$app->user->identity))) {
				Yii::$app->response->redirect('/administration/login');
			}
		}

		return true;
	}

	/**
	 * Проверяет есть ли доступ у пользователя к админке
	 *
	 * @return bool
	 */
	public function hasAccess(User $user)
	{
		return $user->isAdministrator || $user->isModerator;
	}
}