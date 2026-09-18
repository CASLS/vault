<?php

namespace common\models\base;

use Yii;
use common\models\Media;
use common\models\Task;

/**
 * This is the model class for table "ar_target".
*
    * @property integer $id
    * @property string $title
    * @property integer $task_id
    * @property integer $media_id
    * @property double $physical_width
    * @property integer $overlay_media_id
    * @property double $overlay_physical_width
    * @property integer $audio_media_id
    * @property integer $should_auto_close
    * @property integer $close_after
    * @property string $created_at
    * @property string $updated_at
    *
            * @property Media $media
            * @property Media $overlayMedia
            * @property Media $audioMedia
            * @property Task $task
    */
class ArTargetBase extends \yii\db\ActiveRecord
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'ar_target';
}

/**
* @inheritdoc
*/
public function rules()
{
        return [
            [['task_id', 'physical_width'], 'required'],
            [['task_id', 'media_id', 'overlay_media_id', 'audio_media_id', 'should_auto_close', 'close_after'], 'integer'],
            [['physical_width', 'overlay_physical_width'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['media_id'], 'exist', 'skipOnError' => true, 'targetClass' => Media::className(), 'targetAttribute' => ['media_id' => 'id']],
            [['overlay_media_id'], 'exist', 'skipOnError' => true, 'targetClass' => Media::className(), 'targetAttribute' => ['overlay_media_id' => 'id']],
            [['audio_media_id'], 'exist', 'skipOnError' => true, 'targetClass' => Media::className(), 'targetAttribute' => ['audio_media_id' => 'id']],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::className(), 'targetAttribute' => ['task_id' => 'id']],
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
    'task_id' => 'Task ID',
    'media_id' => 'Media ID',
    'physical_width' => 'Physical Width',
    'overlay_media_id' => 'Overlay Media ID',
    'overlay_physical_width' => 'Overlay Physical Width',
    'audio_media_id' => 'Audio Media ID',
    'should_auto_close' => 'Should Auto Close',
    'close_after' => 'Close After',
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
    public function getOverlayMedia()
    {
    return $this->hasOne(Media::className(), ['id' => 'overlay_media_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getAudioMedia()
    {
    return $this->hasOne(Media::className(), ['id' => 'audio_media_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getTask()
    {
    return $this->hasOne(Task::className(), ['id' => 'task_id']);
    }
}