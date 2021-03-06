<?php

namespace app\models;

use yii;
use yii\base\Model;
use app\entities\notification\SenderInterface as NotificationSenderInterface;

/**
 * Форма регистрации
 *
 * @property User|null $user This property is read-only.
 *
 */
class RegistrationForm extends Model implements NotificationSenderInterface
{
    /**
     * Событие регистрации нового пользователя
     */
    const EVENT_NEW = 'new';

    /**
     * Регистрация из админки
     */
    const SCENARIO_BACKEND = 'backend';

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
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_BACKEND] = ['email', 'fio'];

        return $scenarios;
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

        if ($this->scenario == self::SCENARIO_DEFAULT) {
            $user->setPassword($this->password);
        }
        
        if ($user->save()) {
            $user
                ->setRole(User::ROLE_USER)
                ->generateToken();

            Yii::$app->mailer->compose('activation', [
                'user' => $user,
                'activationLink' => Yii::$app->urlManager->createAbsoluteUrl('/activation/' . $user->token)
            ])
                ->setFrom(Yii::$app->params['email']['noreply'])
                ->setTo($user->email)
                ->setSubject('Активация учетной записи')
                ->send();

            $this->success = true;
            
            $this->trigger(self::EVENT_NEW);

            return true;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function getNotificationTemplateVariables()
    {
        return [
            'user.email' => $this->email,
            'user.name'  => $this->fio
        ];
    }
}
