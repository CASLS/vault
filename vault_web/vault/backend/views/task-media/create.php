<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\TaskMedia */

$this->title = 'Create Task Media';
$this->params['breadcrumbs'][] = ['label' => 'Task Media', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-media-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
