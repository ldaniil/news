<?php

namespace app\components;

use yii;

class User extends \yii\web\User
{
	/**
	 * Проверяет является ли пользователь авминистратором
	 *
	 * @return bool
	 */
	public function getIsAdministrator()
	{
		if (!$this->isGuest)
		{
			return $this->getIdentity()->getIsAdministrator();
		}

		return false;
	}
}