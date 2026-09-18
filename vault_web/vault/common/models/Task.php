<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

class Task extends \common\models\base\TaskBase
{
	public $mediaFiles = NULL;
	public $correctImage = NULL;
	public $incorrectImage = NULL;
	
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
    
    public function rules(){
		return array_merge(parent::rules(),[
			[['mediaFiles'], 'file', 'skipOnEmpty'=>true, 'maxFiles' => 3],
			[['correctImage'], 'file', 'skipOnEmpty'=>true, 'maxFiles' => 1],
			[['incorrectImage'], 'file', 'skipOnEmpty'=>true, 'maxFiles' => 1],
		]);
    }
    
	public function attributeLabels(){
		return array_merge(parent::attributeLabels(),[
			"mediaFiles"=>"Media Files",
			"correctImage"=>"Correct Image",
			"incorrectImage"=>"Incorrect Image"
		]);
	}
	
/**
	 * {@inheritdoc}
	 */
	public function save($runValidation = true, $attributeNames = null){
		if($this->correctImage != NULL){
			$newMedia = Media::saveMedia($this->correctImage, \Yii::$app->user->id);
			$this->correct_response_image_id = $newMedia->id;
		}
		if($this->incorrectImage != NULL){
			$newMedia = Media::saveMedia($this->incorrectImage, \Yii::$app->user->id);
			$this->incorrect_response_image_id = $newMedia->id;
		}
		
		return parent::save($runValidation, $attributeNames);
	}

	public function afterSave($insert, $changedAttributes){
		if($this->mediaFiles != NULL && count($this->mediaFiles) > 0){
			foreach($this->mediaFiles as $mediaFile){
				$newMedia = Media::saveMedia($mediaFile, \Yii::$app->user->id);
				$taskMedia = new TaskMedia();
				$taskMedia->task_id = $this->id;
				$taskMedia->media_id = $newMedia->id;
				if(!$taskMedia->save()){
				}
			}
		}
		
		return parent::afterSave($insert, $changedAttributes);
	}
	
/**
	 * {@inheritdoc}
	 */
	public function afterDelete(){
		//Delete all the media models associated to this task.
		foreach($this->taskMedia as $tm){
	    		$media = $tm->media;
	    		$media->delete();
		}
		return parent::afterDelete();
	}
	
}