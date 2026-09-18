<?php

namespace common\models\base;

use Yii;
use common\models\User;

/**
 * This is the model class for table "user_pref".
*
    * @property integer $id
    * @property integer $user_id
    * @property string $label
    * @property string $value
    * @property string $created_at
    * @property string $updated_at
    *
            * @property User $user
    */
class UserPrefBase extends \yii\db\ActiveRecord
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'user_pref';
}

/**
* @inheritdoc
*/
public function rules()
{
        return [
            [['user_id', 'label'], 'required'],
            [['user_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['label'], 'string', 'max' => 255],
            [['value'], 'string', 'max' => 1000],
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
    'label' => 'Label',
    'value' => 'Value',
    'created_at' => 'Created At',
    'updated_at' => 'Updated At',
];
}

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getUser()
    {
    return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}