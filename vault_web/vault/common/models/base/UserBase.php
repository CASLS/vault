<?php

namespace common\models\base;

use Yii;
use common\models\Oauth;
use common\models\QuestUserAccess;
use common\models\UserType;
use common\models\UserEvent;
use common\models\UserMedia;
use common\models\UserPref;
use common\models\UserTask;

/**
 * This is the model class for table "user".
*
    * @property integer $id
    * @property integer $user_type_id
    * @property string $username
    * @property string $password
    * @property string $email
    * @property string $auth_key
    * @property string $password_reset_token
    * @property integer $status
    * @property string $created_at
    * @property string $updated_at
    *
            * @property Oauth[] $oauths
            * @property QuestUserAccess[] $questUserAccesses
            * @property UserType $userType
            * @property UserEvent[] $userEvents
            * @property UserMedia[] $userMedia
            * @property UserPref[] $userPrefs
            * @property UserTask[] $userTasks
    */
class UserBase extends \yii\db\ActiveRecord
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'user';
}

/**
* @inheritdoc
*/
public function rules()
{
        return [
            [['user_type_id', 'username', 'password', 'email'], 'required'],
            [['user_type_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['username', 'email', 'auth_key', 'password_reset_token'], 'string', 'max' => 255],
            [['password'], 'string', 'max' => 1000],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['user_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserType::className(), 'targetAttribute' => ['user_type_id' => 'id']],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => 'ID',
    'user_type_id' => 'User Type ID',
    'username' => 'Username',
    'password' => 'Password',
    'email' => 'Email',
    'auth_key' => 'Auth Key',
    'password_reset_token' => 'Password Reset Token',
    'status' => 'Status',
    'created_at' => 'Created At',
    'updated_at' => 'Updated At',
];
}

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getOauths()
    {
    return $this->hasMany(Oauth::className(), ['user_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getQuestUserAccesses()
    {
    return $this->hasMany(QuestUserAccess::className(), ['user_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getUserType()
    {
    return $this->hasOne(UserType::className(), ['id' => 'user_type_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getUserEvents()
    {
    return $this->hasMany(UserEvent::className(), ['user_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getUserMedia()
    {
    return $this->hasMany(UserMedia::className(), ['user_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getUserPrefs()
    {
    return $this->hasMany(UserPref::className(), ['user_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getUserTasks()
    {
    return $this->hasMany(UserTask::className(), ['user_id' => 'id']);
    }
}