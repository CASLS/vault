<?php

namespace common\models\base;

use Yii;
use common\models\UserTask;

/**
 * This is the model class for table "user_task_history".
*
    * @property integer $id
    * @property integer $user_task_id
    * @property integer $is_complete
    * @property string $response_text
    * @property string $created_at
    *
            * @property UserTask $userTask
    */
class UserTaskHistoryBase extends \yii\db\ActiveRecord
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'user_task_history';
}

/**
* @inheritdoc
*/
public function rules()
{
        return [
            [['user_task_id'], 'required'],
            [['user_task_id', 'is_complete'], 'integer'],
            [['response_text'], 'string'],
            [['created_at'], 'safe'],
            [['user_task_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserTask::className(), 'targetAttribute' => ['user_task_id' => 'id']],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => 'ID',
    'user_task_id' => 'User Task ID',
    'is_complete' => 'Is Complete',
    'response_text' => 'Response Text',
    'created_at' => 'Created At',
];
}

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getUserTask()
    {
    return $this->hasOne(UserTask::className(), ['id' => 'user_task_id']);
    }
}