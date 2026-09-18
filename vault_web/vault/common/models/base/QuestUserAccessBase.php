<?php

namespace common\models\base;

use Yii;
use common\models\Quest;
use common\models\User;

/**
 * This is the model class for table "quest_user_access".
*
    * @property integer $id
    * @property integer $user_id
    * @property integer $quest_id
    * @property integer $is_owner
    * @property integer $permission
    * @property string $created_at
    * @property string $updated_at
    *
            * @property Quest $quest
            * @property User $user
    */
class QuestUserAccessBase extends \yii\db\ActiveRecord
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'quest_user_access';
}

/**
* @inheritdoc
*/
public function rules()
{
        return [
            [['user_id', 'quest_id'], 'required'],
            [['user_id', 'quest_id', 'is_owner', 'permission'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['quest_id'], 'exist', 'skipOnError' => true, 'targetClass' => Quest::className(), 'targetAttribute' => ['quest_id' => 'id']],
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
    'quest_id' => 'Quest ID',
    'is_owner' => 'Is Owner',
    'permission' => 'Permission',
    'created_at' => 'Created At',
    'updated_at' => 'Updated At',
];
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
    public function getUser()
    {
    return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}