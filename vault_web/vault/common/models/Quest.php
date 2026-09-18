<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

class Quest extends \common\models\base\QuestBase
{
	public $unsplashDownloadUrl = "";
	public $questMedia = NULL;
	public $pagingImageMedia = NULL;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
	    	return array_merge(parent::behaviors(),[
    			[
    				'class' => TimestampBehavior::className(),
    				'attributes' => [
    					ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
   						ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
   				],
    				'value' => new Expression('NOW()'),
	    		],
	    	]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function beforeSave($insert)
	{
		if($this->questMedia != NULL){
			$questMedia = Media::saveMedia($this->questMedia, \Yii::$app->user->id);
			if($questMedia != FALSE){
				$this->media_id = $questMedia->id;
			}
		}

		if($this->pagingImageMedia != NULL){
			$pagingImageMedia = Media::saveMedia($this->pagingImageMedia, \Yii::$app->user->id);
			if($pagingImageMedia != FALSE){
				$this->paging_image_id = $pagingImageMedia->id;
			}
		}

		return parent::beforeSave($insert);
	}

	public function rules(){
		return array_merge(parent::rules(),[
			[['questMedia'], 'file', 'skipOnEmpty'=>true, 'maxFiles' => 1],
			[['unsplashDownloadUrl'], 'string'],
			[['pagingImageMedia'], 'file', 'skipOnEmpty'=>true, 'maxFiles' => 1]
		]);
    }
    
	public function attributeLabels(){
		return array_merge(parent::attributeLabels(),[
			"questMedia"=>"Project Media",
			"unsplashDownloadUrl"=>"Unsplash Download URL",
			"pagingImageMedia"=>"Paging Image"
		]);
	}
	
	/**
    * @return \yii\db\ActiveQuery
    */
    public function getTasks()
    {
    return $this->hasMany(Task::className(), ['quest_id' => 'id'])->orderBy('sort_order ASC');
	}
	
	public function getActiveTasks()
    {
    return $this->hasMany(Task::className(), ['quest_id' => 'id'])->orderBy('sort_order ASC')->where(["task.is_active"=>1])->all();
	}
	
	public function generateUniqueCode($length){
		$uniqueStr = self::sanitizeStr(\Yii::$app->security->generateRandomString($length), $length);

		$existingSection = Quest::findOne(["code"=>$uniqueStr]);
		if($existingSection == NULL){
			return $uniqueStr;
		}else{
			return self::generateUniqueCode($length);
		}
	}

	private function sanitizeStr($str, $length){
		$str = str_replace("-",\Yii::$app->security->generateRandomString(1), $str);
		$str = str_replace("_",\Yii::$app->security->generateRandomString(1), $str);
		if (strpos($str, '-') !== false || strpos($str, '_') !== false) {
			return self::sanitizeStr($str,$length);
		}
		return $str;
	}

	public static function isComplete($quest_id, $user_id){
		$quest = \Yii::$app->db->createCommand(
			"SELECT quest.id, quest.name,
			(
				SELECT COUNT(user_task.id) FROM user_task
				JOIN task ON task.id = user_task.task_id
				WHERE user_task.user_id = :user_id1
				AND task.quest_id = quest.id
				AND user_task.is_complete = 0
				AND task.is_active = 1
			) as 'incomplete',

			(
				SELECT COUNT(user_task.id) FROM user_task
				JOIN task ON task.id = user_task.task_id
				WHERE user_task.user_id = :user_id2
				AND task.quest_id = quest.id
				AND user_task.is_complete = 1
				AND task.is_active = 1
			) as 'complete',

			(
				SELECT COUNT(task.id) FROM task
				WHERE task.quest_id = quest.id
				AND task.is_active = 1
			) as 'total'

			FROM quest
			JOIN task ON task.quest_id = quest.id
			JOIN user_task ON user_task.task_id = task.id
			WHERE user_task.user_id = :user_id3
			AND quest.id = :quest_id
			GROUP BY quest.id;",
			[
				":user_id1" => $user_id,
				":user_id2" => $user_id,
				":user_id3" => $user_id,
				":quest_id" => $quest_id,
			]
		)->queryOne();

		if($quest == false || $quest["total"] == 0){
			return 0;
		}

		if($quest["complete"] == $quest["total"]){
			return 1;
		}
		
		return 0;
	}
}