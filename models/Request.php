<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_request".
 *
 * @property integer $id
 * @property string $name
 * @property string $adress
 * @property string $email
 * @property string $phone
 * @property string $date_create
 */
class Request extends \yii\db\ActiveRecord
{
    const NAME_ADDRESS_EMAIL_MAX_LENGTH = 255;
    const PHONE_MAX_LENGTH = 10;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_request';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date_create'], 'safe'],
            [['name', 'adress', 'email'], 'string', 'max' => self::NAME_ADDRESS_EMAIL_MAX_LENGTH],
            [['phone'], 'string', 'max' => self::PHONE_MAX_LENGTH],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'adress' => Yii::t('app', 'Adress'),
            'email' => Yii::t('app', 'Email'),
            'phone' => Yii::t('app', 'Phone'),
            'date_create' => Yii::t('app', 'Date Create'),
        ];
    }

    /**
     * @inheritdoc
     * @return RequestQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new RequestQuery(get_called_class());
    }
}