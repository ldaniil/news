<?php

namespace app\commands;

use yii;
use yii\console\Controller;
use app\models\User;

/**
 * Class RbacController
 *
 * @package app\commands
 */
class RbacController extends Controller
{
	public function actionInit()
	{
		$roles = [
			[
				'name' => User::ROLE_ADMINISTRATOR,
				'description' => 'Администратор'
			],
			[
				'name' => User::ROLE_MODERATOR,
				'description' => 'Модератор'
			],
			[
				'name' => User::ROLE_USER,
				'description' => 'Пользователь'
			],
		];

		$auth = Yii::$app->authManager;

		foreach ($roles as $roleData) {
			if ($auth->getRole($roleData['name'])) {
				continue;
			}

			$role = $auth->createRole($roleData['name']);
			$role->description = $roleData['description'];

			$auth->add($role);
		}
	}
}