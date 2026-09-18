<?php

namespace backend\controllers;

use Yii;
use common\models\UserType;
use common\models\ArTarget;
use common\models\ArTargetSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

/**
 * ArTargetController implements the CRUD actions for ArTarget model.
 */
class ArTargetController extends Controller
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
     * Lists all ArTarget models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ArTargetSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ArTarget model.
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
     * Creates a new ArTarget model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ArTarget();

        if ($model->load(Yii::$app->request->post())){
            $model->mediaFile = UploadedFile::getInstance($model, 'mediaFile');
            $model->overlayMediaFile = UploadedFile::getInstance($model, 'overlayMediaFile');
            $model->audioMediaFile = UploadedFile::getInstance($model, 'audioMediaFile');
            if($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } 

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ArTarget model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())){
            $model->mediaFile = UploadedFile::getInstance($model, 'mediaFile');
            $model->overlayMediaFile = UploadedFile::getInstance($model, 'overlayMediaFile');
            $model->audioMediaFile = UploadedFile::getInstance($model, 'audioMediaFile');
            if($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ArTarget model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        $referringUrl = \Yii::$app->request->referrer;
        if (strpos($referringUrl, 'ar-target') !== true) {
            return $this->redirect($referringUrl);
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the ArTarget model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ArTarget the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ArTarget::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
