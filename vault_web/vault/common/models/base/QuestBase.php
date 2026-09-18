<?php

namespace common\models\base;

use Yii;
use common\models\Media;
use common\models\QuestUserAccess;
use common\models\Task;

/**
 * This is the model class for table "quest".
*
    * @property integer $id
    * @property integer $media_id
    * @property integer $paging_image_id
    * @property string $code
    * @property string $name
    * @property integer $is_active
    * @property string $description
    * @property string $created_at
    * @property string $updated_at
    *
            * @property Media $media
            * @property Media $pagingImage
            * @property QuestUserAccess[] $questUserAccesses
            * @property Task[] $tasks
    */
class QuestBase extends \yii\db\ActiveRecord
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'quest';
}

/**
* @inheritdoc
*/
public function rules()
{
        return [
            [['media_id', 'paging_image_id', 'is_active'], 'integer'],
            [['code', 'name'], 'required'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['code'], 'string', 'max' => 45],
            [['name'], 'string', 'max' => 255],
            [['code'], 'unique'],
            [['media_id'], 'exist', 'skipOnError' => true, 'targetClass' => Media::className(), 'targetAttribute' => ['media_id' => 'id']],
            [['paging_image_id'], 'exist', 'skipOnError' => true, 'targetClass' => Media::className(), 'targetAttribute' => ['paging_image_id' => 'id']],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => 'ID',
    'media_id' => 'Media ID',
    'paging_image_id' => 'Paging Image ID',
    'code' => 'Code',
    'name' => 'Name',
    'is_active' => 'Is Active',
    'description' => 'Description',
    'created_at' => 'Created At',
    'updated_at' => 'Updated At',
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
    public function getPagingImage()
    {
    return $this->hasOne(Media::className(), ['id' => 'paging_image_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getQuestUserAccesses()
    {
    return $this->hasMany(QuestUserAccess::className(), ['quest_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getTasks()
    {
    return $this->hasMany(Task::className(), ['quest_id' => 'id']);
    }
}