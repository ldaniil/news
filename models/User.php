<?php

namespace app\models;

use yii;
use yii\base\Exception;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Class User
 *
 * @package app\models
 *
 * @property integer    $id
 * @property string     $email
 * @property string     $password
 * @property int        $status
 * @property string     $token
 * @property string     $fio
 * @property int        $create_at
 * @property int        $updated_at
 *
 */
class User extends ActiveRecord implements \yii\web\IdentityInterface
{
    const ROLE_ADMINISTRATOR = 'administrator'; // Администратор
    const ROLE_USER = 'user';                 // Пользователь

    const STATUS_DELETED = 0;   // Удален
    const STATUS_REGISTRED = 1; // Зарегистрирован
    const STATUS_ACTIVE = 2;    // Активнен

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
     * Проверяет является ли пользователь авминистратором
     *
     * @return bool
     */
    public function getIsAdministrator()
    {
        if (!$this->isNewRecord) {

            $roles = Yii::$app->authManager->getRolesByUser($this->id);

            foreach ($roles as $role) {
                if ($role->name == User::ROLE_ADMINISTRATOR) {
                    return true;
                }
            }
        }

        return false;
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
