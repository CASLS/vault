<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

class UserMedia extends \common\models\base\UserMediaBase
{
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
}