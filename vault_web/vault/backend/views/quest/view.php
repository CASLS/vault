<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\QuestUserAccess;

/* @var $this yii\web\View */
/* @var $model common\models\Quest */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Quests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="quest-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php 
    $userAccess = QuestUserAccess::findOne([
        'quest_id'=>$model->id, 
        'user_id'=>\Yii::$app->user->id
    ]);
    ?>
    <p>
        <?php 
        $userAccess = QuestUserAccess::findOne([
            'quest_id'=>$model->id, 
            'user_id'=>\Yii::$app->user->id
        ]);
        if( ($userAccess != NULL && $userAccess->permission == QuestUserAccess::PERMISSION_EDIT) 
            || (\Yii::$app->user->identity->user_type_id == 1 || \Yii::$app->user->identity->user_type_id == 2)){
        
            echo Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
            echo " ";
        }
        if( ($userAccess != NULL && $userAccess->is_owner == 1) 
            || (\Yii::$app->user->identity->user_type_id == 1 || \Yii::$app->user->identity->user_type_id == 2)){
       
            echo Html::a('Delete', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post',
                    ],
                ]);
        } 
        ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'media_id',
            'paging_image_id',
            'code',
            'name',
            'is_active',
            'description:html',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
