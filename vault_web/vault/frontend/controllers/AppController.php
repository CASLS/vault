<?php

namespace frontend\controllers;

class AppController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //Redirect to the app store listing configured in params.php (e.g. for a QR-code landing link).
        return $this->redirect(\Yii::$app->params['appStoreUrl']);
    }

}
