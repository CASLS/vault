<?php

namespace common\models\base;

use Yii;
use common\models\User;

/**
 * This is the model class for table "user_event".
*
    * @property integer $id
    * @property integer $user_id
    * @property string $event_type
    * @property string $event_detail
    * @property string $ip
    * @property string $browser
    * @property string $url
    * @property string $referring_url
    * @property string $created_at
    *
            * @property User $user
    */
class UserEventBase extends \yii\db\ActiveRecord
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'user_event';
}

/**
* @inheritdoc
*/
public function rules()
{
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['event_detail'], 'string'],
            [['created_at'], 'safe'],
            [['event_type', 'ip', 'browser', 'url', 'referring_url'], 'string', 'max' => 1000],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => 'ID',
    'user_id' => 'User ID',
    'event_type' => 'Event Type',
    'event_detail' => 'Event Detail',
    'ip' => 'Ip',
    'browser' => 'Browser',
    'url' => 'Url',
    'referring_url' => 'Referring Url',
    'created_at' => 'Created At',
];
}

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getUser()
    {
    return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}