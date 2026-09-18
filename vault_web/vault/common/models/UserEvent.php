<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

class UserEvent extends \common\models\base\UserEventBase
{
	const TYPE_LOGIN = 'login';
	const TYPE_LOGOUT = 'logout';	
	const TYPE_MEDIA_DELETE = 'media_delete';
	const TYPE_MEDIA_RECORD = 'media_record';
	const TYPE_MEDIA_SAVE = 'media_save';
	const TYPE_MEDIA_UPLOAD = 'media_upload';
	const TYPE_REQUEST_ACCESS_TOKEN = 'request_access_token';
	const TYPE_REQUEST_PASSWORD_RESET = 'request_password_reset';
	const TYPE_RESET_PASSWORD = 'reset_password';
	const TYPE_SIGN_UP = 'sign_up';
	const TYPE_GOOGLE_SIGN_UP = 'google_sign_up';
	const TYPE_GOOGLE_LOGIN = 'google_login';
	const TYPE_APPLE_SIGN_UP = 'apple_sign_up';
	const TYPE_APPLE_LOGIN = 'apple_login';
	const TYPE_GUEST_LOGIN = 'guest_login';
	const TYPE_SAVE_USER_TASK = 'save_user_task';
	const TYPE_GET_TASK_LISTS = 'get_task_lists';
	const TYPE_SAVE_UNSPLASH_MEDIA = "save_unsplash_media";
	const TYPE_SAVE_UNSPLASH_MEDIA_FAILED = "save_unsplash_media_failed";
    
	/**
     * {@inheritdoc}
     */
    public function behaviors()
    {
	    	return array_merge(parent::behaviors(),[
    			[
    				'class' => TimestampBehavior::className(),
    				'attributes' => [
    					ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
   				],
    				'value' => new Expression('NOW()'),
	    		],
	    	]);
    }
    
    /**
     * Helper method for saving user events, like 'login', 'view page', etc.
	 * Also stores additional information about this request into the loginevent table.
	 * 
     * @param int $user_id
     * @param string $event_type
     * @param string $event_detail
     */
	
	public static function saveUserEvent($user_id, $event_type, $event_detail = null) {
		
		//just in case they are not set
		$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
		$useragent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
		$uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
		$referringurl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
		
		$userEvent = new UserEvent();
		$userEvent->user_id = ($user_id == null) ? 0 : $user_id;
		$userEvent->event_type = $event_type;
		$userEvent->event_detail = $event_detail;
		$userEvent->ip = $ip;
		$userEvent->browser = $useragent;
		$userEvent->url = $uri;
		$userEvent->referring_url = $referringurl;
		$userEvent->save();
	}
}