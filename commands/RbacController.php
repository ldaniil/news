<?php

namespace app\commands;

use yii;
use yii\console\Controller;
use app\models\User;

class RbacController extends Controller
{
	public function actionInit()
	{
		$auth = Yii::$app->authManager;

		$admin = $auth->createRole(User::ROLE_ADMINISTRATOR);
		$admin->description = 'Администратор';
		$auth->add($admin);

		$user = $auth->createRole(User::ROLE_USER);
		$user->description = 'Пользователь';
		$auth->add($user);
	}
}