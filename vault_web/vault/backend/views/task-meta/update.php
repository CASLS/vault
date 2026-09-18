<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\TaskMeta */

$this->title = 'Update Task Meta: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Task Metas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="task-meta-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
