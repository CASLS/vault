<?php

namespace common\models\base;

use Yii;
use common\models\ResponseMedia;
use common\models\Task;
use common\models\User;
use common\models\UserTaskHistory;

/**
 * This is the model class for table "user_task".
*
    * @property integer $id
    * @property integer $user_id
    * @property integer $task_id
    * @property integer $is_complete
    * @property string $response_text
    * @property string $created_at
    * @property string $updated_at
    *
            * @property ResponseMedia[] $responseMedia
            * @property Task $task
            * @property User $user
            * @property UserTaskHistory[] $userTaskHistories
    */
class UserTaskBase extends \yii\db\ActiveRecord
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'user_task';
}

/**
* @inheritdoc
*/
public function rules()
{
        return [
            [['user_id', 'task_id'], 'required'],
            [['user_id', 'task_id', 'is_complete'], 'integer'],
            [['response_text'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::className(), 'targetAttribute' => ['task_id' => 'id']],
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
    'task_id' => 'Task ID',
    'is_complete' => 'Is Complete',
    'response_text' => 'Response Text',
    'created_at' => 'Created At',
    'updated_at' => 'Updated At',
];
}

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getResponseMedia()
    {
    return $this->hasMany(ResponseMedia::className(), ['user_task_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getTask()
    {
    return $this->hasOne(Task::className(), ['id' => 'task_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getUser()
    {
    return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getUserTaskHistories()
    {
    return $this->hasMany(UserTaskHistory::className(), ['user_task_id' => 'id']);
    }
}