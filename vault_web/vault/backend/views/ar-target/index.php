<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ArTargetSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ar Targets';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ar-target-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Ar Target', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            'task_id',
            'media_id',
            'physical_width',
            //'overlay_media_id',
            //'overlay_physical_width',
            //'audio_media_id',
            //'should_auto_close',
            //'close_after',
            //'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
