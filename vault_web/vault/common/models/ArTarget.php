<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

class ArTarget extends \common\models\base\ArTargetBase
{
    public $mediaFile = NULL;
    public $overlayMediaFile = NULL;
    public $audioMediaFile = NULL;

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
			[['mediaFile'], 'file', 'skipOnEmpty'=>true, 'maxFiles' => 1],
            [['overlayMediaFile'], 'file', 'skipOnEmpty'=>true, 'maxFiles' => 1],
            [['audioMediaFile'], 'file', 'skipOnEmpty'=>true, 'maxFiles' => 1]
		]);
    }
    
	public function attributeLabels(){
		return array_merge(parent::attributeLabels(),[
			"mediaFile"=>"Media File",
            "overlayMediaFile"=>"Overlay Media File",
            "audioMediaFile"=>"Audio Media File"
		]);
    }
    
    /**
	 * {@inheritdoc}
	 */
	public function save($runValidation = true, $attributeNames = null){
        if($this->mediaFile != NULL){
            $newMedia = Media::saveMedia($this->mediaFile, \Yii::$app->user->id);
            $this->media_id = $newMedia->id;
        }
        if($this->overlayMediaFile != NULL){
            $newMedia = Media::saveMedia($this->overlayMediaFile, \Yii::$app->user->id);
            $this->overlay_media_id = $newMedia->id;
        }
        if($this->audioMediaFile != NULL){
            $newMedia = Media::saveMedia($this->audioMediaFile, \Yii::$app->user->id);
            $this->audio_media_id = $newMedia->id;
        }
        
        return parent::save($runValidation, $attributeNames);
    }
            
    /**
     * {@inheritdoc}
     */
    public function afterDelete(){
        //Delete all the media models associated to this ar target.
        $this->media->delete();
        if($this->overlayMedia != NULL){
            $this->overlayMedia->delete();
        }
        if($this->audioMedia != NULL){
            $this->audioMedia->delete();
        }
        return parent::afterDelete();
    }
}