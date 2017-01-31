<?php

namespace app\models;

use yii;
use yii\base\Exception;
use yii\base\Model;

class ActivationForm extends Model
{
	const SCENARIO_SET_PASSWORD = 'setPassword';

	/**
	 * @var User
	 */
	public $user;

	/**
	 * @var string
	 */
	public $password;

	/**
	 * @var string
	 */
	public $confirmPassword;

	public function init()
	{
		parent::init();

		if (!($this->user instanceof User)) {
			throw new Exception('Undefined user');
		}

		if (empty($this->user->password)) {
			$this->setScenario(self::SCENARIO_SET_PASSWORD);
		}
	}

	public function rules() {
		return [
			['password', 'required'],
			['password', 'validatePassword'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function scenarios()
	{
		$scenarios = parent::scenarios();
		$scenarios[self::SCENARIO_DEFAULT] = [];
		$scenarios[self::SCENARIO_SET_PASSWORD] = ['password', 'confirmPassword'];

		return $scenarios;
	}

	public function attributeLabels()
	{
		return [
			'password'   	  => 'Пароль',
			'confirmPassword' => 'Подтвердите пароль'
		];
	}

	/**
	 * Проверка пароля
	 *
	 * @param string $attribute the attribute currently being validated
	 * @param array $params the additional name-value pairs given in the rule
	 */
    public function validatePassword($attribute, $params)
	{
		if ($this->password != $this->confirmPassword) {
			$this->addError($attribute, 'Пароли не совпадают.');
		}
	}

	/**
	 * Активация пользователя
	 */
	public function activate()
	{
		if (!$this->validate()) {
			return false;
		}

		$setPassword = true;

		if ($this->scenario == self::SCENARIO_SET_PASSWORD) {
			$this->user->password = User::generatePasswordHash($this->password);
			$setPassword = $this->user->save(false, ['password']);
		}

		if ($setPassword && $this->user->activate()) {
			Yii::$app->user->login($this->user, 3600 * 24 * 30);
			return true;
		} else {
			return false;
		}
	}
}