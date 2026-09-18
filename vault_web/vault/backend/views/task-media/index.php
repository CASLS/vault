<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\TaskMediaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Task Media';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-media-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Task Media', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'task_id',
            'media_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
