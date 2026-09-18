<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\QuestUserAccess;

/* @var $this yii\web\View */
/* @var $searchModel common\models\QuestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Quests';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="quest-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Quest', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'media_id',
            'paging_image_id',
            'code',
            'name',
            'is_active',
            'created_at',
            'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'visibleButtons'=>[
                    'update' => function ($model, $key, $index) {
                        $userAccess = QuestUserAccess::findOne([
                            'quest_id'=>$model->id, 
                            'user_id'=>\Yii::$app->user->id
                        ]);
                        if($userAccess != NULL && $userAccess->permission == QuestUserAccess::PERMISSION_EDIT){
                            return true;
                        }else if(\Yii::$app->user->identity->user_type_id == 1 || \Yii::$app->user->identity->user_type_id == 2){
                            return true;
                        }
                        return false;
                    },   
                    'delete' => function ($model, $key, $index) {
                        $userAccess = QuestUserAccess::findOne([
                            'quest_id'=>$model->id, 
                            'user_id'=>\Yii::$app->user->id
                        ]);
                        if($userAccess != NULL && $userAccess->is_owner == 1){
                            return true;
                        }else if(\Yii::$app->user->identity->user_type_id == 1 || \Yii::$app->user->identity->user_type_id == 2){
                            return true;
                        }
                        return false;
                    },   
                ]
            ],
        ],
    ]); ?>


</div>
