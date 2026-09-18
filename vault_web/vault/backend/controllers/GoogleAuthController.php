<?php
namespace backend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Response;
use common\models\User;
use common\models\UserType;
use common\models\Oauth;

/**
 * Google Auth controller
 *
 * Disabled: the frontend entry points (asset registration, sign-in buttons, meta tag) that
 * reach this controller are commented out, because the JS side relied on gapi.auth2, which
 * Google retired March 31, 2023. This controller's logic is left intact for reference and to
 * make a future migration to Google Identity Services (GSI) easier, but is currently unreachable.
 */
class GoogleAuthController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function behaviors()
	{
		return [
			// Both actions here are part of the pre-login Google sign-in flow, so
			// they're intentionally open to guests.
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'actions' => ['check-for-account', 'token-sign-in'],
						'allow' => true,
					],
				],
			],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function actions()
	{
		return [
			'error' => [
				'class' => 'yii\web\ErrorAction',
			],
		];
	}
	
	public function actionCheckForAccount($email){
		\Yii::$app->response->format = Response::FORMAT_JSON;
		
		$user = User::findOne(["email"=>$email]);
		if($user != NULL){
			return [ 
						'returnCode' => 0,
						'returnCodeDescription' => "Success",
						'data' => [ 
							'user'=>$user
						]
				];
		}else{
			return [ 
						'returnCode' => 1,
						'returnCodeDescription' => "No user found.",
						'data' => [ 
							'user'=>NULL
						]
				];
		}
	}
	
	public function actionTokenSignIn(){
		\Yii::$app->response->format = Response::FORMAT_JSON;
		// Get $id_token via HTTPS POST.
		$id_token = \Yii::$app->request->post ( "id_token" );
		$client = new \Google_Client ( [ 
				'client_id' => "YOUR_GOOGLE_OAUTH_CLIENT_ID.apps.googleusercontent.com"
		] ); // Specify the CLIENT_ID of the app that accesses the backend
		$payload = $client->verifyIdToken ( $id_token );
		if ($payload) {
			$user = User::findOne ( [
					"email" => $payload ["email"]
			] );
			if ($user == NULL) {
				// Sign up as a new user
				$user = new User ();
				$user->username = $payload ["email"];
				$user->email = $payload ["email"];
				$user->user_type_id = UserType::USER;
				$user->status = User::STATUS_ACTIVE;
				$user->setPassword ( \Yii::$app->security->generateRandomString ( 32 ) );
				$user->generateAuthKey ();
				if (! $user->save ()) {
					return [ 
							'returnCode' => 1,
							'returnCodeDescription' => "Error: Could not create a new user.",
							'data' => [ ]
					];
				}
			}
			
			$oauth = Oauth::findOne([
					"user_id"=>$user->id,
					"uid"=>$payload["sub"],
					"source"=>$payload["iss"]
			]);
			
			if($oauth == NULL){
				$oauth = new Oauth();
				$oauth->user_id = $user->id;
				$oauth->uid = $payload["sub"];
				$oauth->source = $payload["iss"];
				$oauth->token_type = "bearer";
				$oauth->expires_in = (string)$payload["exp"];
				$oauth->state = "Active";
			}
			//Set or update the access_token value.
			$oauth->access_token = $id_token;
			
			if(!$oauth->save()){
				return [ 
							'returnCode' => 1,
							'returnCodeDescription' => "Error: Could not save oauth entry.",
							'data' => [ ]
					];
			}
			
			//Log the user in for the session.
			\Yii::$app->user->login($user, 3600 * 24 * 30);
			
			// Block user type 3 (regular users) from accessing backend
			if ($user->user_type_id == 3) {
				\Yii::$app->user->logout();
				return [
					'returnCode' => 1,
					'returnCodeDescription' => "Please log in using the Vault app.",
					'data' => []
				];
			}
			
			//Store the ID token/access_token and the source in the SESSION
			$session = Yii::$app->session;
			$session->set("access_token", $oauth->access_token);
			$session->set("source",$oauth->source);

			return [ 
					'returnCode' => 0,
					'returnCodeDescription' => "Success!",
					'data' => [ 
							'payload' => $payload
					]
			];
		} else {
			// Invalid ID token
			return [ 
					'returnCode' => 1,
					'returnCodeDescription' => "Error: Invalid ID token",
					'data' => [ ]
			];
		}
	}
}
