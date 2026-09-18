<?php

namespace backend\controllers;

use Yii;
use common\models\Quest;
use common\models\QuestSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Task;
use yii\web\UploadedFile;
use common\models\TaskMeta;
use common\models\ArTarget;
use common\models\User;
use common\models\QuestUserAccess;
use common\models\Media;

/**
 * QuestController implements the CRUD actions for Quest model.
 */
class QuestController extends Controller
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
                        'roles' => ['@'],
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
     * Lists all Quest models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new QuestSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Quest model.
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
     * Creates a new Quest model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Quest();
        $model->code = $model->generateUniqueCode(8);

        if ($model->load(Yii::$app->request->post())) {
            $model->questMedia = UploadedFile::getInstance($model, "questMedia");
            $model->pagingImageMedia = UploadedFile::getInstance($model, "pagingImageMedia");
            if($model->save()){
                //Create the associated owner QuestUserAccess model
                $questUserAccess = new QuestUserAccess();
                $questUserAccess->user_id = \Yii::$app->user->id; //The logged in user's id.
                $questUserAccess->quest_id = $model->id;
                $questUserAccess->is_owner = 1;
                $questUserAccess->permission = QuestUserAccess::PERMISSION_EDIT;
                if(!$questUserAccess->save()){
                }
                return $this->redirect(['view', 'id' => $model->id]);   
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Quest model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if($model->code == "" || $model->code == NULL){
            $model->code = $model->generateUniqueCode(8);
        }
        
   	 	$taskArray = \Yii::$app->request->post("Task");
   	 	$taskId = null;
        if($taskArray != null){
            $task = new Task();
            if($taskArray["id"] != null){
                $task = Task::findOne($taskArray["id"]);
            }
            $task->load(\Yii::$app->request->post());
            $task->mediaFiles = UploadedFile::getInstances($task, 'mediaFiles');
            $task->correctImage = UploadedFile::getInstance($task, 'correctImage');
            $task->incorrectImage = UploadedFile::getInstance($task, 'incorrectImage');
            if($task->validate()){
                if(!$task->save()){
                }
                $taskId = $task->id; 
            }else{
            }
        }
        
        $taskMetaArray = \Yii::$app->request->post("TaskMeta");
        if($taskMetaArray != null){
            $taskMeta = new TaskMeta();
            if($taskMetaArray["id"] != null){
                $taskMeta = TaskMeta::findOne($taskMetaArray["id"]);
            }
            $taskMeta->load(\Yii::$app->request->post());
            if($taskMeta->validate()){
                if(!$taskMeta->save()){
                }
                $taskId = $taskMeta->task_id;
            }
        }

        $arTargetArray = \Yii::$app->request->post("ArTarget");
        if($arTargetArray != null){
        	$arTarget = new ArTarget();
        	if($arTargetArray["id"] != null){
        		$arTarget = ArTarget::findOne($arTargetArray["id"]);
        	}
            $arTarget->load(\Yii::$app->request->post());
            $arTarget->mediaFile = UploadedFile::getInstance($arTarget, 'mediaFile');
            $arTarget->overlayMediaFile = UploadedFile::getInstance($arTarget, 'overlayMediaFile');
            $arTarget->audioMediaFile = UploadedFile::getInstance($arTarget, 'audioMediaFile');
        	if($arTarget->validate()){
        		if(!$arTarget->save()){
        		}
        	}
        }

        $editor = User::findOne(\Yii::$app->user->id);
        $editorAccess = QuestUserAccess::find()->where(["user_id"=>$editor->id,"quest_id"=>$model->id])->one();
	        
        if ($model->load(Yii::$app->request->post())) {
            $model->questMedia = UploadedFile::getInstance($model, "questMedia");
            $model->pagingImageMedia = UploadedFile::getInstance($model, "pagingImageMedia");
            if($model->save()){
                return $this->render('update', [
                    'model' => $model,
                    "taskId"=>$taskId,
                    "editor"=>$editor,
                    "editorAccess"=>$editorAccess
                ]);
            }else{
                return $this->render('update', [
                    'model' => $model,
                    "taskId"=>$taskId,
                    "editor"=>$editor,
                    "editorAccess"=>$editorAccess
                ]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            "taskId"=>$taskId,
            "editor"=>$editor,
            "editorAccess"=>$editorAccess
        ]);
    }

    public function actionRemoveMedia(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $media_id = \Yii::$app->request->post("media_id");
        $quest_id = \Yii::$app->request->post("quest_id");

        if($quest_id == NULL || $quest_id == ""){
			return [
				'returnCode'=>1, 
				'returnCodeDescription'=>"Invalid parameter: quest_id", 
				'data'=>[], 
			];
        }

        if($media_id == NULL || $media_id == ""){
			return [
				'returnCode'=>1, 
				'returnCodeDescription'=>"Invalid parameter: media_id", 
				'data'=>[], 
			];
        }

        $media = Media::findOne($media_id);
        if($media != NULL){
            if($media->delete()){
                //This will cascade UPDATE the media_id in the corresponding Quest table.
                return [
                    'returnCode'=>0, 
                    'returnCodeDescription'=>"Success!", 
                    'data'=>[], 
                ];
            }else{
                return [
                    'returnCode'=>1, 
                    'returnCodeDescription'=>"Could not delete the media object.", 
                    'data'=>[], 
                ];
            }
        }else{
            return [
                'returnCode'=>1, 
                'returnCodeDescription'=>"Could not find the media object.", 
                'data'=>[], 
            ];
        }
    }

    public function actionGiveAccess(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $user_id = \Yii::$app->request->post("user_id");
        $quest_id = \Yii::$app->request->post("quest_id");
        $is_owner = \Yii::$app->request->post("is_owner");
        $permission = \Yii::$app->request->post("permission");

        if($quest_id == NULL || $quest_id == ""){
			return [
				'returnCode'=>1, 
				'returnCodeDescription'=>"Invalid parameter: quest_id", 
				'data'=>[], 
			];
        }

        if($user_id == NULL || $user_id == ""){
			return [
				'returnCode'=>1, 
				'returnCodeDescription'=>"Invalid parameter: user_id", 
				'data'=>[], 
			];
        }

        if($is_owner == NULL || $is_owner == ""){
			return [
				'returnCode'=>1, 
				'returnCodeDescription'=>"Invalid parameter: is_owner", 
				'data'=>[], 
			];
        }

        if($permission == NULL || $permission == ""){
			return [
				'returnCode'=>1, 
				'returnCodeDescription'=>"Invalid parameter: permission", 
				'data'=>[], 
			];
        }
        
        $questUserAccess = QuestUserAccess::find()->where(["user_id"=>$user_id,"quest_id"=>$quest_id])->one();
        if($questUserAccess == NULL){
            $questUserAccess = new QuestUserAccess();
            $questUserAccess->user_id = $user_id;
            $questUserAccess->quest_id = $quest_id;
        }
        $questUserAccess->is_owner = $is_owner; //This updates the value if it already exists.
        $questUserAccess->permission = $permission; //This updates the value if it already exists.

        if($questUserAccess->save() == false){
            return [
                'returnCode'=>1, 
                'returnCodeDescription'=>"Error saving user access.", 
                'data'=>[
                    
                ], 
            ];
        }

        //Fetch the saved object. This is a fix for NULL created_at values.
        $questUserAccess = QuestUserAccess::find()->where(["user_id"=>$user_id,"quest_id"=>$quest_id])->one();
        $html =  $this->renderPartial("/quest-user-access/_row",[
                        "user"=>$questUserAccess->user,
                        "quest"=>$questUserAccess->quest,
                        "qua"=>$questUserAccess
                ]);

        return [
            'returnCode'=>0, 
            'returnCodeDescription'=>"success", 
            'data'=>[
                "questUserAccess"=>$questUserAccess,
                "html"=>$html
            ], 
        ];
    }

    public function actionRemoveAccess(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $user_id = \Yii::$app->request->post("user_id");
        $quest_id = \Yii::$app->request->post("quest_id");

        if($quest_id == NULL || $quest_id == ""){
			return [
				'returnCode'=>1, 
				'returnCodeDescription'=>"Invalid parameter: quest_id", 
				'data'=>[], 
			];
        }

        if($user_id == NULL || $user_id == ""){
			return [
				'returnCode'=>1, 
				'returnCodeDescription'=>"Invalid parameter: user_id", 
				'data'=>[], 
			];
        }

        $questUserAccess = QuestUserAccess::find()->where(["user_id"=>$user_id,"quest_id"=>$quest_id])->one();
        if($questUserAccess == NULL){
            return [
				'returnCode'=>1, 
				'returnCodeDescription'=>"Error finding user access.", 
				'data'=>[], 
			];
        }

        if($questUserAccess->delete() == false){
            return [
				'returnCode'=>1, 
				'returnCodeDescription'=>"Error deleting user access.", 
				'data'=>[], 
			];
        }

        return [
            'returnCode'=>0, 
            'returnCodeDescription'=>"success", 
            'data'=>[
                
            ], 
    ];
    }

    public function actionGenerateCodes(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $quests = Quest::find()->where(["code"=>""])->all();
        foreach($quests as $quest){
            $quest->code = $quest->generateUniqueCode(8);
            if(!$quest->save()){
            }
        }

        return "Complete.";

    }

    /**
     * Deletes an existing Quest model.
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
     * Finds the Quest model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Quest the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Quest::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
