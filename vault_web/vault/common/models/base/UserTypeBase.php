<?php

namespace common\models\base;

use Yii;
use common\models\User;

/**
 * This is the model class for table "user_type".
*
    * @property integer $id
    * @property string $name
    *
            * @property User[] $users
    */
class UserTypeBase extends \yii\db\ActiveRecord
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'user_type';
}

/**
* @inheritdoc
*/
public function rules()
{
        return [
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
    public function getUsers()
    {
    return $this->hasMany(User::className(), ['user_type_id' => 'id']);
    }
}