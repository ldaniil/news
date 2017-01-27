<?php

namespace app\modules\backend\models;

use yii;
use app\models\User;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends \app\models\LoginForm
{
    /**
     * @return array
     */
    public function attributeLabels()
    {
        $labels = parent::attributeLabels();
        $labels['username'] = 'Логин';

        return $labels;
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password) || !Yii::$app->controller->module->hasAccess($user)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }
}