<?php

namespace common\models\base;

use Yii;
use common\models\Task;

/**
 * This is the model class for table "required_task".
*
    * @property integer $id
    * @property integer $parent_task_id
    * @property integer $child_task_id
    * @property string $created_at
    *
            * @property Task $parentTask
            * @property Task $childTask
    */
class RequiredTaskBase extends \yii\db\ActiveRecord
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'required_task';
}

/**
* @inheritdoc
*/
public function rules()
{
        return [
            [['parent_task_id', 'child_task_id'], 'required'],
            [['parent_task_id', 'child_task_id'], 'integer'],
            [['created_at'], 'safe'],
            [['parent_task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::className(), 'targetAttribute' => ['parent_task_id' => 'id']],
            [['child_task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::className(), 'targetAttribute' => ['child_task_id' => 'id']],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => 'ID',
    'parent_task_id' => 'Parent Task ID',
    'child_task_id' => 'Child Task ID',
    'created_at' => 'Created At',
];
}

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getParentTask()
    {
    return $this->hasOne(Task::className(), ['id' => 'parent_task_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getChildTask()
    {
    return $this->hasOne(Task::className(), ['id' => 'child_task_id']);
    }
}