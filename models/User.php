<?php

namespace app\models;

use yii;
use yii\base\Exception;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use app\entities\notification\recipient\EmailInterface;
use app\entities\notification\recipient\BrowserInterface;
use app\models\query\UserQuery;

/**
 * Class User
 *
 * @package app\models
 *
 * @property integer     $id
 * @property string      $email
 * @property string      $password
 * @property int         $status
 * @property string      $token
 * @property string      $fio
 * @property int         $create_at
 * @property int         $updated_at
 * @property null|string $role
 *
 */
class User extends ActiveRecord implements \yii\web\IdentityInterface, EmailInterface, BrowserInterface
{
    const ROLE_ADMINISTRATOR = 'administrator'; // Администратор
    const ROLE_MODERATOR = 'moderator';         // Модератор
    const ROLE_USER = 'user';                   // Пользователь

    const STATUS_DELETED = 0;   // Удален
    const STATUS_REGISTRED = 1; // Зарегистрирован
    const STATUS_ACTIVE = 2;    // Активнен

    /**
     * Название ролей
     *
     * @var array
     */
    public static $statusLabels = [
        self::STATUS_DELETED   => 'Удален',
        self::STATUS_REGISTRED => 'Зарегистрирован',
        self::STATUS_ACTIVE    => 'Активирован',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * Генерирует хеш пароля
     *
     * @param string $password пароль
     *
     * @return mixed
     */
    public static function generatePasswordHash($password)
    {
        return md5($password);
    }

    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Поиск пользователя по email
     *
     * @param $email
     *
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Поиск по токену
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByToken($token)
    {
        return static::findOne(['token' => $token]);
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'unique'],
            ['email', 'required', 'on' => 'update', 'message' => 'Введите электронный адрес'],

            ['fio', 'filter', 'filter' => 'trim'],
            ['fio', 'required', 'on' => 'update', 'message' => 'Введите имя'],

            ['status', 'default', 'value' => self::STATUS_REGISTRED],
            ['status', 'in', 'range' => [self::STATUS_DELETED, self::STATUS_REGISTRED, self::STATUS_ACTIVE,]],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fio'        => 'ФИО',
            'status'     => 'Статус',
            'created_at' => 'Дата регистрации',
        ];
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Возвращает логин пользователя
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->token;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->token === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === self::generatePasswordHash($password);
    }

    /**
     * Устанавливает пароль
     *
     * @param string $password
     *
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = self::generatePasswordHash($password);

        return $this;
    }

    /**
     * Устанавливает роль
     *
     * @param string $name название роли
     *
     * @return $this
     * @throws Exception
     */
    public function setRole($name)
    {
        if ($this->isNewRecord) {
            throw new Exception('Нельзя устанавливать роль не созданому пользователю');
        }

        if (!empty($this->role)) {
            throw new Exception('У пользоватяля уже установлена роль: ' . $this->role);
        }

        $auth = Yii::$app->authManager;
        $role = $auth->getRole($name);

        $auth->assign($role, $this->id);

        return $this;
    }

    /**
     * Генерирует и устанавливает код подтверждения операций
     *
     * @return $this
     */
    public function generateToken()
    {
        $this->token = md5(time() . rand(111111111, 999999999));

        $this->update(false, ['token']);

        return $this;
    }

    /**
     * Активирует учетную запись пользователя
     *
     * @throws \Exception
     */
    public function activate()
    {
        $this->status = User::STATUS_ACTIVE;
        $this->token = null;

        $this->update(false, ['status', 'token']);

        return true;
    }

    /**
     * Возвращает идентификатор пользователя
     * 
     * @return int
     */
    public function getUserId()
    {
        return $this->id;
    }

    /**
     * Возвращает роль
     *
     * @return string
     */
    public function getRole()
    {
        return $this
            ->hasOne(AuthAssignment::className(), ['user_id' => 'id'])
            ->from(AuthAssignment::tableName() . ' role');
    }

    /**
     * Возвращает имя
     *
     * @return string
     */
    public function getName()
    {
        return $this->fio;
    }

    /**
     * Возвращает email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Возвращает название статуса
     *
     * @return mixed
     */
    public function getStatusLabel()
    {
        return self::$statusLabels[$this->status];
    }

    /**
     * Проверяет является ли пользователь авминистратором
     *
     * @return bool
     */
    public function getIsAdministrator()
    {
        return $this->role && $this->role->name == User::ROLE_ADMINISTRATOR;
    }

    /**
     * Проверяет является ли пользователь модератором
     *
     * @return bool
     */
    public function getIsModerator()
    {
        return $this->role && $this->role->name == User::ROLE_MODERATOR;
    }

    /**
     * Проверякт активен ли пользователь
     *
     * @return bool
     */
    public function getIsActive()
    {
        return $this->status == self::STATUS_ACTIVE;
    }
}
