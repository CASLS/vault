<?php

namespace common\models\base;

use Yii;
use common\models\Task;

/**
 * This is the model class for table "task_meta".
*
    * @property integer $id
    * @property integer $task_id
    * @property string $key
    * @property string $value
    * @property string $created_at
    * @property string $updated_at
    *
            * @property Task $task
    */
class TaskMetaBase extends \yii\db\ActiveRecord
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'task_meta';
}

/**
* @inheritdoc
*/
public function rules()
{
        return [
            [['task_id', 'key', 'value'], 'required'],
            [['task_id'], 'integer'],
            [['value'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['key'], 'string', 'max' => 255],
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
    'key' => 'Key',
    'value' => 'Value',
    'created_at' => 'Created At',
    'updated_at' => 'Updated At',
];
}

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getTask()
    {
    return $this->hasOne(Task::className(), ['id' => 'task_id']);
    }
}