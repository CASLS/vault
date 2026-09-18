<?php

namespace common\models\base;

use Yii;
use common\models\Media;
use common\models\Task;

/**
 * This is the model class for table "task_media".
*
    * @property integer $id
    * @property integer $task_id
    * @property integer $media_id
    *
            * @property Media $media
            * @property Task $task
    */
class TaskMediaBase extends \yii\db\ActiveRecord
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'task_media';
}

/**
* @inheritdoc
*/
public function rules()
{
        return [
            [['task_id', 'media_id'], 'required'],
            [['task_id', 'media_id'], 'integer'],
            [['media_id'], 'exist', 'skipOnError' => true, 'targetClass' => Media::className(), 'targetAttribute' => ['media_id' => 'id']],
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
    'task_id' => 'Task ID',
    'media_id' => 'Media ID',
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
    public function getTask()
    {
    return $this->hasOne(Task::className(), ['id' => 'task_id']);
    }
}