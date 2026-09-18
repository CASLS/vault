<?php

namespace backend\controllers;

use Yii;
use common\models\Task;
use common\models\TaskSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use yii\web\Response;
use common\models\TaskMedia;
use common\models\ArTarget;
use common\models\RequiredTask;

/**
 * TaskController implements the CRUD actions for Task model.
 */
class TaskController extends Controller
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

    public function actionRemoveMedia(){
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $task_media_id = \Yii::$app->request->post('task_media_id');
        if($task_media_id == NULL || $task_media_id == ""){
            return [
                'returnCode' => 1, 
                'returnCodeDescription' => "Invalid parameters: task_media_id", 
                'data' => [
                    
                ]
            ];	
        }

        $taskMedia = TaskMedia::findOne($task_media_id);
        if($taskMedia == NULL){
            return [
                'returnCode' => 1, 
                'returnCodeDescription' => "Error: Could not find TaskMedia object.", 
                'data' => [
                    
                ]
            ];
        }
        
        if($taskMedia->delete()){
            return [
                'returnCode' => 0, 
                'returnCodeDescription' => "Success", 
                'data' => [
                    
                ]
            ];	
        }else{
            return [
                'returnCode' => 1, 
                'returnCodeDescription' => "Error trying to delete TaskMedia object ({$task_media_id})", 
                'data' => [
                    
                ]
            ];	
        }
    }

    /**
     * Lists all Task models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TaskSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Task model.
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
     * Creates a new Task model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Task();

        if ($model->load(Yii::$app->request->post())){
        		$model->mediaFiles = UploadedFile::getInstances($model, 'mediaFiles');
        		$model->correctImage = UploadedFile::getInstance($model, 'correctImage');
        		$model->incorrectImage = UploadedFile::getInstance($model, 'incorrectImage');
        		if($model->save()) {
           		return $this->redirect(['view', 'id' => $model->id]);
        		}
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Task model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

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
        	if($arTarget->validate()){
        		if(!$arTarget->save()){
        		}
        	}
        }
        
        if ($model->load(Yii::$app->request->post())){
        		$model->mediaFiles = UploadedFile::getInstances($model, 'mediaFiles');
        		$model->correctImage = UploadedFile::getInstance($model, 'correctImage');
        		$model->incorrectImage = UploadedFile::getInstance($model, 'incorrectImage');
        		if($model->save()) {
            		return $this->redirect(['view', 'id' => $model->id]);
        		}
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionUpdateRequiredTasks(){
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $parent_task_id = \Yii::$app->request->post('parent_task_id');
        if($parent_task_id == NULL || $parent_task_id == ""){
            return [
                'returnCode' => 1, 
                'returnCodeDescription' => "Invalid parameters: parent_task_id", 
                'data' => [
                    
                ]
            ];	
        }

        $requiredTasks = \Yii::$app->request->post('requiredTasks');
        if($requiredTasks == NULL){
            $requiredTasks = [];	
        }

        //remove required tasks
        $parentTask = Task::findOne($parent_task_id);
        if($parentTask == NULL){
            return [
                'returnCode' => 1, 
                'returnCodeDescription' => "Parent task not found", 
                'data' => [
                    
                ]
            ];
        }
        //get the current required task IDs, compare them to the new set
        $currentRequiredTaskIDs = [];
        foreach($parentTask->requiredTasks as $rt){
            $currentRequiredTaskIDs[] = $rt->child_task_id;
        }
        
        //Any that exist int he current set but NOT in the new set should be deleted
        $toBeDeleted = array_diff($currentRequiredTaskIDs, $requiredTasks);
        
        //Any that are in the new set but NOT in the current set should be added.
        $toBeAdded = array_diff($requiredTasks, $currentRequiredTaskIDs);

        //Loop required tasks to be deleted
        foreach($toBeDeleted as $child_task_id){
            $requiredTask = RequiredTask::findOne([
                                "parent_task_id"=>$parent_task_id,
                                "child_task_id"=>$child_task_id
            ]);
            if($requiredTask->delete() == false){
            }

        }
        //Loop required tasks to be added
        foreach($toBeAdded as $child_task_id){
            $requiredTask = RequiredTask::findOne([
                                "parent_task_id"=>$parent_task_id,
                                "child_task_id"=>$child_task_id
            ]);
            if($requiredTask == NULL){
                $requiredTask = new RequiredTask();
                $requiredTask->parent_task_id = $parent_task_id;
                $requiredTask->child_task_id = $child_task_id;
                if($requiredTask->save() == false){
                }
            }
        }

        return [
            'returnCode' => 0, 
            'returnCodeDescription' => "Success", 
            'data' => [

            ]
        ];
    }

    /**
     * Deletes an existing Task model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        if(strpos(\Yii::$app->request->referrer, '/quest/update') !== false){
            return $this->redirect(\Yii::$app->request->referrer);
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Task model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Task the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Task::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
