<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use Yii;


class User extends ActiveRecord implements IdentityInterface
{
    public $id;
    public $username;
    public $name;
    public $surname;
    public $password;
    public $salt;
    public $access_token;
    public $create_date;

    const MIN_LENGTH_PASS = 6;

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
            'id' => _('ID'),
            'username' => _('Логин'),
            'name' => _('Имя'),
            'surname' => _('Фамилия'),
            'password' => _('Пароль'),
            'salt' => _('Соль'),
            'access_token' => _('Ключ аутентификации')
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
    public function validatePassword($password)
    {
        return $this->password === $password;
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
     * 
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
}
