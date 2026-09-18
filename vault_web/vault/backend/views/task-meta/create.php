<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\TaskMeta */

$this->title = 'Create Task Meta';
$this->params['breadcrumbs'][] = ['label' => 'Task Metas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-meta-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
