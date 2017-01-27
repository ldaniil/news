<?php

namespace app\components;

use yii;
use app\models\User as UserModel;

class User extends \yii\web\User
{
	/**
	 * Проверяет является ли пользователь авминистратором
	 *
	 * @return bool
	 */
	public function getIsAdministrator()
	{
		return $this->isRole(UserModel::ROLE_ADMINISTRATOR);
	}

	/**
	 * Проверяет является ли пользователь авминистратором
	 *
	 * @return bool
	 */
	public function getIsModerator()
	{
		return $this->isRole(UserModel::ROLE_MODERATOR);
	}

	/**
	 * Проверяет к какой роли пренадлежит текущий пользователь
	 *
	 * @param $role
	 *
	 * @return bool
	 */
	protected function isRole($role)
	{
		if (!$this->isGuest) {
			$property = 'is' . ucfirst($role);
			return $this->getIdentity()->$property;
		} else {
			return false;
		}
	}
}