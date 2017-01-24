<?php

namespace app\models;

use yii;
use yii\base\Model;

/**
 * Форма регистрации
 *
 * @property User|null $user This property is read-only.
 *
 */
class RegistrationForm extends Model
{
    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $password;

    /**
     * @var string
     */
    public $confirmPassword;

    /**
     * @var string
     */
    public $fio;

    /**
     * @var bool
     */
    public $success = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['email', 'password', 'confirmPassword', 'fio'], 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => User::class, 'targetAttribute' => 'email'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'username'        => 'Email',
            'password'        => 'Пароль',
            'confirmPassword' => 'Повторите пароль',
            'fio'             => 'Имя'
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
        if (!$this->hasErrors()) {
            if ($this->password != $this->confirmPassword) {
                $this->addError($attribute, 'Пароли не совпадают.');
            }
        }
    }

    /**
     * Регестрирует нового пользователя
     *
     * @return bool
     * @throws yii\base\Exception
     */
    public function register()
    {
        if (!$this->validate()) {
            return false;
        }
        
        $user = new User([
            'email'     => $this->email,
            'fio'       => $this->fio,
            'status'    => User::STATUS_REGISTRED,
        ]);
        $user->setPassword($this->password);
        
        if ($user->save()) {
            $user
                ->setRole(User::ROLE_USER)
                ->generateToken();

            Yii::$app->mailer->compose('activation', [
                'user' => $user,
                'activationLink' => Yii::$app->urlManager->createAbsoluteUrl('/activation/' . $user->token)
            ])
                ->setFrom('from@domain.com')
                ->setTo($user->email)
                ->setSubject('Активация учетной записи')
                ->send();

            $this->success = true;

            return true;
        }

        return false;
    }
}
