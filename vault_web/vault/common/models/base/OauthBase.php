<?php

namespace common\models\base;

use Yii;
use common\models\User;

/**
 * This is the model class for table "oauth".
*
    * @property integer $id
    * @property integer $user_id
    * @property string $access_token
    * @property string $token_type
    * @property string $uid
    * @property string $expires_in
    * @property string $source
    * @property string $state
    * @property string $created_at
    * @property string $updated_at
    *
            * @property User $user
    */
class OauthBase extends \yii\db\ActiveRecord
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'oauth';
}

/**
* @inheritdoc
*/
public function rules()
{
        return [
            [['user_id', 'access_token', 'uid', 'source'], 'required'],
            [['user_id'], 'integer'],
            [['access_token'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['token_type', 'uid', 'expires_in', 'source', 'state'], 'string', 'max' => 255],
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
    'access_token' => 'Access Token',
    'token_type' => 'Token Type',
    'uid' => 'Uid',
    'expires_in' => 'Expires In',
    'source' => 'Source',
    'state' => 'State',
    'created_at' => 'Created At',
    'updated_at' => 'Updated At',
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