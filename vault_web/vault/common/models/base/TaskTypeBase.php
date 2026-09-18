<?php

namespace common\models\base;

use Yii;
use common\models\Task;

/**
 * This is the model class for table "task_type".
*
    * @property integer $id
    * @property string $name
    *
            * @property Task[] $tasks
    */
class TaskTypeBase extends \yii\db\ActiveRecord
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'task_type';
}

/**
* @inheritdoc
*/
public function rules()
{
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => 'ID',
    'name' => 'Name',
];
}

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getTasks()
    {
    return $this->hasMany(Task::className(), ['task_type_id' => 'id']);
    }
}