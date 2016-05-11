<?php

namespace app\models;

use Yii;
use app\models\query\AccessQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "clndr_access".
 *
 * @property integer $id
 * @property integer $user_owner
 * @property integer $user_guest
 * @property string $date
 *
 * @property User $userGuest
 * @property User $userOwner
 */
class Access extends ActiveRecord
{

    const ACCESS_CREATOR = 1;
    const ACCESS_GUEST = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'clndr_access';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_guest', 'date'], 'required'],
            [['user_owner', 'user_guest'], 'integer'],
            [['date'], 'safe'],
            [['user_guest'], 'exist', 'skipOnError' => false, 'targetClass' => User::className(), 'targetAttribute' => ['user_guest' => 'id']],
            [['user_owner'], 'exist', 'skipOnError' => false, 'targetClass' => User::className(), 'targetAttribute' => ['user_owner' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_owner' => Yii::t('app', 'Владелец'),
            'user_guest' => Yii::t('app', 'Гость'),
            'date' => Yii::t('app', 'Дата'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserGuest()
    {
        return $this->hasMany(User::className(), ['id' => 'user_guest']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserOwner()
    {
        return $this->hasOne(User::className(), ['id' => 'user_owner']);
    }

    /**
     * @inheritdoc
     * @return \app\models\query\AccessQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AccessQuery(get_called_class());
    }

    /**
     * Before save condition allows to set user_owner id to current user id
     * @param bool $insert
     * @return bool
     */
    public function beforeSave ($insert)
    {
        if ($this->getIsNewRecord())
        {
            $this->user_owner = Yii::$app->user->id;
        }
        parent::beforeSave($insert);
        return true;
    }

    /**
     * Check access for Calendar note
     *
     * @param Calendar $model
     * @return bool|int
     */
    public static function checkAccess($model)
    {
        if ($model->creator == Yii::$app->user->id){
            return self::ACCESS_CREATOR;
        }

        $accessCalendar = self::find()
            ->withUserGuest(Yii::$app->user->id)
            ->withSharedDate($model->getDateEventStart())
            ->exists();

        if ($accessCalendar)
            return self::ACCESS_GUEST;

        return false;
    }

    /**
     * Check logged user is creator or not
     *
     * @param Calendar $model
     * @return bool
     */
    public static function checkIsCreator ($model)
    {
        return self::checkAccess($model) == self::ACCESS_CREATOR;
    }

}
