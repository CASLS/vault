<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\RequiredTask */

$this->title = 'Create Required Task';
$this->params['breadcrumbs'][] = ['label' => 'Required Tasks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="required-task-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
