<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Task */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Tasks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="task-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'quest_id',
            'task_type_id',
            'title',
            'body:html',
            'answer',
            'auto_complete',
            'is_active',
            'sort_order',
            'correct_response_text',
            'incorrect_response_text',
        		[
        			'attribute'=>'Media',
        			'format'=>'raw',
        			'value'=>function($data){
        						$html = "";
						    foreach($data->taskMedia as $tm){
	    							$media = $tm->media;
					    			if($media->type == "image"){
					    				$html .= Html::img($media->url,["style"=>"max-width:200px;"]);
					    			}else if($media->type == "video"){
					    				$html .= "<video controls style='max-width:100%;max-height:310px;'>" .
											"<source src='{$media->url}' type='{$media->mime_type}'>" .
										 "</video>";
					    			}else if($media->type == "audio"){
					    				$html .= "<audio controls>" . 
											"<source src='{$media->url}' type='{$media->mime_type}'>" . 
										 "</audio>";
					    			}
					    		}
        						return $html;
    				}
    			],
            'correct_response_image_id',
            'incorrect_response_image_id',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
