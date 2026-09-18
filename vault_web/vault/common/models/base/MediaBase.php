<?php

namespace common\models\base;

use Yii;
use common\models\ArTarget;
use common\models\Quest;
use common\models\ResponseMedia;
use common\models\Task;
use common\models\TaskMedia;
use common\models\UserMedia;

/**
 * This is the model class for table "media".
*
    * @property integer $id
    * @property string $title
    * @property string $uri
    * @property string $url
    * @property string $type
    * @property string $mime_type
    * @property integer $file_size
    * @property double $duration
    * @property string $created_at
    * @property string $updated_at
    *
            * @property ArTarget[] $arTargets
            * @property ArTarget[] $arTargets0
            * @property ArTarget[] $arTargets1
            * @property Quest[] $quests
            * @property Quest[] $quests0
            * @property ResponseMedia[] $responseMedia
            * @property Task[] $tasks
            * @property Task[] $tasks0
            * @property TaskMedia[] $taskMedia
            * @property UserMedia[] $userMedia
    */
class MediaBase extends \yii\db\ActiveRecord
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'media';
}

/**
* @inheritdoc
*/
public function rules()
{
        return [
            [['title', 'uri', 'url', 'type', 'mime_type', 'file_size'], 'required'],
            [['file_size'], 'integer'],
            [['duration'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['uri', 'url', 'mime_type'], 'string', 'max' => 1000],
            [['type'], 'string', 'max' => 45],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => 'ID',
    'title' => 'Title',
    'uri' => 'Uri',
    'url' => 'Url',
    'type' => 'Type',
    'mime_type' => 'Mime Type',
    'file_size' => 'File Size',
    'duration' => 'Duration',
    'created_at' => 'Created At',
    'updated_at' => 'Updated At',
];
}

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getArTargets()
    {
    return $this->hasMany(ArTarget::className(), ['media_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getArTargets0()
    {
    return $this->hasMany(ArTarget::className(), ['overlay_media_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getArTargets1()
    {
    return $this->hasMany(ArTarget::className(), ['audio_media_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getQuests()
    {
    return $this->hasMany(Quest::className(), ['media_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getQuests0()
    {
    return $this->hasMany(Quest::className(), ['paging_image_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getResponseMedia()
    {
    return $this->hasMany(ResponseMedia::className(), ['media_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getTasks()
    {
    return $this->hasMany(Task::className(), ['correct_response_image_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getTasks0()
    {
    return $this->hasMany(Task::className(), ['incorrect_response_image_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getTaskMedia()
    {
    return $this->hasMany(TaskMedia::className(), ['media_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getUserMedia()
    {
    return $this->hasMany(UserMedia::className(), ['media_id' => 'id']);
    }
}