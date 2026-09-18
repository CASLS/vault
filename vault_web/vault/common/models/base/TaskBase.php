<?php

namespace common\models\base;

use Yii;
use common\models\ArTarget;
use common\models\RequiredTask;
use common\models\Media;
use common\models\Quest;
use common\models\TaskType;
use common\models\TaskMedia;
use common\models\TaskMeta;
use common\models\UserTask;

/**
 * This is the model class for table "task".
*
    * @property integer $id
    * @property integer $quest_id
    * @property integer $task_type_id
    * @property string $title
    * @property string $body
    * @property string $answer
    * @property integer $auto_complete
    * @property integer $is_active
    * @property double $sort_order
    * @property string $correct_response_text
    * @property string $incorrect_response_text
    * @property integer $correct_response_image_id
    * @property integer $incorrect_response_image_id
    * @property string $created_at
    * @property string $updated_at
    *
            * @property ArTarget[] $arTargets
            * @property RequiredTask[] $requiredTasks
            * @property RequiredTask[] $requiredTasks0
            * @property Media $correctResponseImage
            * @property Media $incorrectResponseImage
            * @property Quest $quest
            * @property TaskType $taskType
            * @property TaskMedia[] $taskMedia
            * @property TaskMeta[] $taskMetas
            * @property UserTask[] $userTasks
    */
class TaskBase extends \yii\db\ActiveRecord
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'task';
}

/**
* @inheritdoc
*/
public function rules()
{
        return [
            [['quest_id', 'task_type_id', 'title', 'body'], 'required'],
            [['quest_id', 'task_type_id', 'auto_complete', 'is_active', 'correct_response_image_id', 'incorrect_response_image_id'], 'integer'],
            [['body'], 'string'],
            [['sort_order'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['title', 'answer'], 'string', 'max' => 255],
            [['correct_response_text', 'incorrect_response_text'], 'string', 'max' => 1000],
            [['correct_response_image_id'], 'exist', 'skipOnError' => true, 'targetClass' => Media::className(), 'targetAttribute' => ['correct_response_image_id' => 'id']],
            [['incorrect_response_image_id'], 'exist', 'skipOnError' => true, 'targetClass' => Media::className(), 'targetAttribute' => ['incorrect_response_image_id' => 'id']],
            [['quest_id'], 'exist', 'skipOnError' => true, 'targetClass' => Quest::className(), 'targetAttribute' => ['quest_id' => 'id']],
            [['task_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => TaskType::className(), 'targetAttribute' => ['task_type_id' => 'id']],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => 'ID',
    'quest_id' => 'Quest ID',
    'task_type_id' => 'Task Type ID',
    'title' => 'Title',
    'body' => 'Body',
    'answer' => 'Answer',
    'auto_complete' => 'Auto Complete',
    'is_active' => 'Is Active',
    'sort_order' => 'Sort Order',
    'correct_response_text' => 'Correct Response Text',
    'incorrect_response_text' => 'Incorrect Response Text',
    'correct_response_image_id' => 'Correct Response Image ID',
    'incorrect_response_image_id' => 'Incorrect Response Image ID',
    'created_at' => 'Created At',
    'updated_at' => 'Updated At',
];
}

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getArTargets()
    {
    return $this->hasMany(ArTarget::className(), ['task_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getRequiredTasks()
    {
    return $this->hasMany(RequiredTask::className(), ['parent_task_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getRequiredTasks0()
    {
    return $this->hasMany(RequiredTask::className(), ['child_task_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getCorrectResponseImage()
    {
    return $this->hasOne(Media::className(), ['id' => 'correct_response_image_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getIncorrectResponseImage()
    {
    return $this->hasOne(Media::className(), ['id' => 'incorrect_response_image_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getQuest()
    {
    return $this->hasOne(Quest::className(), ['id' => 'quest_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getTaskType()
    {
    return $this->hasOne(TaskType::className(), ['id' => 'task_type_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getTaskMedia()
    {
    return $this->hasMany(TaskMedia::className(), ['task_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getTaskMetas()
    {
    return $this->hasMany(TaskMeta::className(), ['task_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getUserTasks()
    {
    return $this->hasMany(UserTask::className(), ['task_id' => 'id']);
    }
}