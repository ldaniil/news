<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Class AuthAssignment
 *
 * @package app\models
 *
 * @property string item_name
 * @property int    user_id
 * @property int 	created_at
 * @property string name
 */
class AuthAssignment extends ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return '{{%auth_assignment}}';
	}

	public function getName()
	{
		return $this->item_name;
	}
}