<?php

namespace backend\controllers;

use Yii;
use common\models\UserType;
use common\models\Media;
use common\models\MediaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\User;
use yii\web\UploadedFile;
use yii\web\Response;
use common\models\UserEvent;
use yii\helpers\FileHelper;

/**
 * MediaController implements the CRUD actions for Media model.
 */
class MediaController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
        	'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['error'],
                        'allow' => true,
                    ],
                    [
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            $identity = Yii::$app->user->identity;
                            return $identity !== null && in_array($identity->user_type_id, [UserType::SUPER_ADMIN, UserType::ADMIN], true);
                        },
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Media models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MediaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionMediaUploadAjax(){
    	\Yii::$app->response->format = Response::FORMAT_JSON;

		$user_id = \Yii::$app->getUser()->id;
		$user = User::findOne($user_id);
    	$mediaFile = UploadedFile::getInstanceByName("mediaFile");
    	if($mediaFile == NULL){
    		return ['returnCode'=>1, 'returnCodeDescription'=>"No file data found.", 'data'=>["errors"=>""], ];
    	}
    	$newMedia = Media::saveMedia($mediaFile, $user_id);

		if($newMedia == false){
			return ['returnCode'=>1, 'returnCodeDescription'=>"Failed to save media file.", 'data'=>["errors"=>""], ];	
    	}
    		
    	return  ['returnCode'=>0, 'returnCodeDescription'=>"Success", 'data'=>["mediaFile"=>$newMedia]];
    }

    /**
     * Guards against SSRF: only allow fetching http(s) URLs that resolve to a
     * public IP address, so this endpoint can't be used to reach internal
     * network/cloud infrastructure or the local filesystem (e.g. file://).
     */
    private function isSafeDownloadUrl($url)
    {
        $parts = is_string($url) ? parse_url($url) : false;
        if ($parts === false || empty($parts['scheme']) || empty($parts['host'])) {
            return false;
        }
        if (!in_array(strtolower($parts['scheme']), ['http', 'https'], true)) {
            return false;
        }

        //parse_url() keeps the brackets on an IPv6 literal host; strip them before
        //validating so bracketed public IPv6 hosts aren't mistaken for unresolvable names.
        $host = $parts['host'];
        $hostForIpCheck = trim($host, '[]');
        $isIpLiteral = filter_var($hostForIpCheck, FILTER_VALIDATE_IP) !== false;
        $ip = $isIpLiteral ? $hostForIpCheck : gethostbyname($host);

        //gethostbyname() returns the input unchanged if it couldn't resolve it.
        if (!$isIpLiteral && $ip === $host) {
            return false;
        }

        //Reject loopback/private/link-local/reserved ranges to block SSRF.
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false;
    }

    public function actionDownloadMediaFromUrl(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $downloadUrl = \Yii::$app->request->post("downloadUrl"); //Download URL sent via $_POST

        if (!$this->isSafeDownloadUrl($downloadUrl)) {
            return ['returnCode'=>1, 'returnCodeDescription'=>"Invalid or disallowed download URL.", 'data'=>[]];
        }

        $user_id = \Yii::$app->user->id;
		$user = User::findOne($user_id);
		$newMedia = NULL;
    	
		$filename = basename($downloadUrl); // Getting the base name of the file.
		
		$basicType = "image";
		$extension = "jpg";
		$mimeType = "image/jpeg";
		if($user_id != null){
			$newFileName = $user_id . "_" . date('Y-m-d-His') . "_" . rand(100,999);	
		}else{
			$newFileName = "0000_" . date('Y-m-d-His') . "_" . rand(100,999);
		}
		//expand the directory structure to include a sub-dir layer for YYYYMM/DD/ to alleviate bloated directories over time.
		$year = date('Y');
		$month = date('m');
		$dayOfMonth = date('d');

		//Create the upload directory for the image.
		$newFilePath = \Yii::getAlias('@app/../common/web/uploads/' . $user_id . '/' . $basicType . '/' . $year . '/' . $month . '/' . $dayOfMonth . '/');
		if(FileHelper::createDirectory($newFilePath)){
			
		}else{
			return ['returnCode'=>1, 'returnCodeDescription'=>"Error saving media.", 'data'=>[]];;
		}
		
		$uri = $newFilePath . $newFileName  . "." . $extension;
		$url = '/common/uploads/' . $user_id . '/' . $basicType . '/' . $year . '/' . $month . '/' . $dayOfMonth . '/' . $newFileName  . "." . $extension;

		//Redirects are disabled and the response status is checked below so a URL that
		//passed isSafeDownloadUrl() can't redirect the actual fetch to an internal host.
		$context = stream_context_create([
			'http' => [
				'method' => 'GET',
				'follow_location' => 0,
				'timeout' => 10,
			],
		]);
		$fileContents = @file_get_contents($downloadUrl, false, $context);
		$statusLine = isset($http_response_header[0]) ? $http_response_header[0] : '';
		if($fileContents === false || !preg_match('{^HTTP/\S+\s+2\d\d}', $statusLine)){
			return ['returnCode'=>1, 'returnCodeDescription'=>"Error downloading media from URL.", 'data'=>[]];
		}
		$result = file_put_contents($uri, $fileContents);
		if($result === false){
			return ['returnCode'=>1, 'returnCodeDescription'=>"Error saving media to disk.", 'data'=>[]];
		}else{
			
			if($basicType == "audio" || $basicType == "video"){
				// Initialize getID3 engine
		       	$getID3 = new \getID3();
		       	$fileInfo = $getID3->analyze($uri);
		        $duration = $fileInfo["playtime_seconds"];
	        	$file_size = $fileInfo["filesize"];
			}else{
				$duration = NULL;
	        	$file_size = filesize($uri);
			}
			
			//Create a Media entry in the database.
			$newMedia = new Media();
			$newMedia->title = $newFileName  . "." . $extension;
			$newMedia->uri = $uri;
			$newMedia->url = $url;
			$newMedia->type = $basicType;
			$newMedia->mime_type = $mimeType;
			$newMedia->duration = $duration;
			$newMedia->file_size = $file_size;
			if($newMedia->save()){
    			UserEvent::saveUserEvent($user_id, UserEvent::TYPE_SAVE_UNSPLASH_MEDIA, "Unsplash media url was used to download the media file. Media ID={$newMedia->id}");
				return ['returnCode'=>0, 'returnCodeDescription'=>"Success", 'data'=>["media"=>$newMedia]];
			}else{
				UserEvent::saveUserEvent($user_id, UserEvent::TYPE_SAVE_UNSPLASH_MEDIA_FAILED, "Media object was unable to save. Media ID={$newMedia->id}");
				return ['returnCode'=>1, 'returnCodeDescription'=>"Error saving media.", 'data'=>[]];
			}
		}
    }

    /**
     * Displays a single Media model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Media model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Media();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Media model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Media model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Media model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Media the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Media::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
