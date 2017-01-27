<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Class NotificationSettingModel
 *
 * @package app\models
 *
 * @property int 	$id
 * @property string $model
 * @property string $event
 * @property string $title
 * @property string $message
 * @property string $routes
 */
class NotificationSettingModel extends ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return '{{%notification_setting}}';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['model', 'event', 'title', 'message'], 'required'],
			['model', 'unique', 'targetAttribute' => ['model', 'event']],
			['routes', 'safe'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'model'   => 'Модель',
			'event'   => 'Событие',
			'title'   => 'Заголовок',
			'message' => 'Сообщение'
		];
	}

	/**
	 * @param bool $insert
	 *
	 * @return bool
	 */
	public function beforeSave($insert)
	{
		$this->routes = json_encode($this->routes);

		return parent::beforeSave($insert);
	}

	/**
	 * @inheritdoc
	 */
	public function afterFind()
	{
		parent::afterFind();

		$this->routes = json_decode($this->routes, true);
	}

	/**
	 * @inheritdoc
	 */
	public function load($data, $formName = null)
	{
		$scope = $formName === null ? $this->formName() : $formName;

		if (!empty($data[$scope]['routes'])) {
			foreach ($data[$scope]['routes'] as $name => $route) {
				$route['role'] = $name;
				$route['enable'] = !(empty($route['enable'])) ? $route['enable'] : 0;
				$route['exclude'] = !(empty($route['exclude'])) ? explode(',', $route['exclude']) : [];
				$route['transports'] = !(empty($route['transports'])) ? array_keys($route['transports']) : [];

				$data[$scope]['routes'][$name] = $route;
			}
		}

		return $result = parent::load($data, $formName);
	}
}