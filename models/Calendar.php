<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use app\models\query\CalendarQuery;

/**
 * This is the model class for table "clndr_calendar".
 *
 * @property integer $id
 * @property string $text
 * @property integer $creator
 * @property string $date_event_start
 * @property string $date_event_end
 *
 * @property User $user
 */
class Calendar extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'clndr_calendar';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['text'], 'required'],
            [['text'], 'string'],
            [['creator'], 'integer'],
            [['date_event_start', 'date_event_end'], 'safe'],
            [['creator'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['creator' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'text' => Yii::t('app', 'Событие'),
            'creator' => Yii::t('app', 'Владелец'),
            'date_event_start' => Yii::t('app', 'Начало события'),
            'date_event_end' => Yii::t('app', 'Конец события'),
            'user_name' => Yii::t('app', 'Владелец')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'creator']);
    }

    /**
     * @inheritdoc
     * @return \app\models\query\CalendarQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CalendarQuery(get_called_class());
    }

    /**
    * Before save condition allows to set creators id to current user id
    * @param bool $insert
    * @return bool
    */
    public function beforeSave ($insert)
    {
        if ($this->getIsNewRecord())
        {
            $this->creator = Yii::$app->user->id;
        }
        parent::beforeSave($insert);
        return true;
    }

    /**
     * Return date in format for Access checking
     *
     * @return mixed
     */
    public function getDateEventStart()
    {
        $date = new \DateTime($this->date_event_start);
        return $date->format('Y-m-d');
    }

    /**
     * Return date start in format for view
     *
     * @return mixed
     */
    public function getDateTimeEventStart()
    {
        $date = new \DateTime($this->date_event_start);
        return $date->format('d/m/Y h:m');
    }

    /**
     * Return date start in format for view
     *
     * @return mixed
     */
    public function getDateTimeEventEnd()
    {
        $date = new \DateTime($this->date_event_end);
        return $date->format('d/m/Y h:m');
    }

}
