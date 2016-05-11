<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use Yii;
use app\models\query\UserQuery;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $surname
 * @property string $name
 * @property string $password write-only password
 * @property string $salt
 * @property string $access_token
 * @property string $create_date
 *
 * @property array $sharedDates
 *
 */

class User extends ActiveRecord implements IdentityInterface
{
    const MIN_LENGTH_PASS = 6;
    const MAX_LENGTH_USERNAME = 128;
    const MAX_LENGTH_NAME_SURNAME = 45;

    /**
     * Table
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'clndr_user';
    }

    /**
     * Rules
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'name', 'surname', 'password'], 'required'],
            [['password'], 'string', 'min' => self::MIN_LENGTH_PASS],
            [['username'], 'string', 'max' => self::MAX_LENGTH_USERNAME],
            [['name', 'surname'], 'string', 'max' => self::MAX_LENGTH_NAME_SURNAME],
            [['username'], 'email'],
            [['username', 'access_token'], 'unique']
        ];
    }

    /**
     * Labels
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Логин'),
            'name' => Yii::t('app', 'Имя'),
            'surname' => Yii::t('app', 'Фамилия'),
            'password' => Yii::t('app', 'Пароль'),
            'salt' => Yii::t('app', 'Соль'),
            'access_token' => Yii::t('app', 'Ключ аутентификации'),
            'create_date' => Yii::t('app', 'Дата регистрации')
        ];
    }

    /**
     * Before save event handler
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert))
        {
            if($this->getIsNewRecord() && !empty($this->password))
            {
                $this->salt = $this->saltGenerator();
            }
            if(!empty($this->password))
            {
                $this->password = $this->passWithSalt($this->password, $this->salt);
            }
            else
            {
                unset($this->password);
            }
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }


    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param  string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->access_token;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword ($password)
    {
        return $this->password === $this->passWithSalt($password, $this->salt);
    }

    /**
     *  Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->access_token = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates salt
     * @return mixed
     */
    public function saltGenerator()
    {
        return hash('sha512', uniqid('salt_', true));
    }

    /**
     * Unnecessary function
     * @param $password
     */
    public function setPassword ($password) {
        $this->salt = $this->saltGenerator();
        $this->password = $this->passWithSalt($password, $this->salt);
    }

    /**
     * Returns pass with salt
     * @param $password
     * @param $salt
     * @return string
     */
    public function passWithSalt($password, $salt)
    {
        return hash('sha512', $password . $salt);
    }

    /**
     * Calls ActiveQuery child
     * @return UserQuery
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }
}
