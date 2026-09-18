<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\TaskSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tasks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Task', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'quest_id',
            'task_type_id',
            'title',
//             'body:html',
        		[	'attribute'=>'body',
            		'format'=>'raw',
        			'contentOptions'=>[
        				'style'=>'max-width:200px;white-space:normal;'		
    				],
        			'headerOptions'=>[
        				'style'=>'max-width:200px;'		
    				]
            ],
            //'answer',
            //'is_active',
            //'sort_order',
            //'correct_response_text',
            //'incorrect_response_text',
            //'correct_response_image_id',
            //'incorrect_response_image_id',
            //'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
