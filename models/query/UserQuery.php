<?php

namespace app\models\query;

use yii\db\ActiveQuery;
use app\models\User;

/**
 * Class UserQuery
 *
 * @package app\models\query
 */
class UserQuery extends ActiveQuery
{
	/**
	 * Ищет автивных
	 *
	 * @return $this
	 */
	public function byActive()
	{
		return $this->andWhere('status = :status', [':status' => User::STATUS_ACTIVE]);
	}

	/**
	 * Ищет по роли
	 *
	 * @param string $role
	 *
	 * @return $this
	 */
	public function byRole($role)
	{
		return $this
			->joinWith('role')
			->andWhere('role.item_name = :role',  [':role' => $role]);
	}

	/**
	 * Ищет по email
	 *
	 * @param string $email
	 * @param bool $revert
	 *
	 * @return $this
	 */
	public function byEmail($email, $revert = false)
	{
		$id = (array)$email;

		$operator = !$revert ? 'in' : 'not in';

		return $this->andWhere([$operator, 'email', $id]);
	}
}