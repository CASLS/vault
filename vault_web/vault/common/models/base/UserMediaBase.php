<?php

namespace common\models\base;

use Yii;
use common\models\Media;
use common\models\User;

/**
 * This is the model class for table "user_media".
*
    * @property integer $id
    * @property integer $user_id
    * @property integer $media_id
    * @property string $title
    * @property string $created_at
    * @property string $updated_at
    *
            * @property Media $media
            * @property User $user
    */
class UserMediaBase extends \yii\db\ActiveRecord
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'user_media';
}

/**
* @inheritdoc
*/
public function rules()
{
        return [
            [['user_id', 'media_id'], 'required'],
            [['user_id', 'media_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['media_id'], 'exist', 'skipOnError' => true, 'targetClass' => Media::className(), 'targetAttribute' => ['media_id' => 'id']],
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
    'media_id' => 'Media ID',
    'title' => 'Title',
    'created_at' => 'Created At',
    'updated_at' => 'Updated At',
];
}

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getMedia()
    {
    return $this->hasOne(Media::className(), ['id' => 'media_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getUser()
    {
    return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}