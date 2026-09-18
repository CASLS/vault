<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\UserEvent */

$this->title = 'Update User Event: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'User Events', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-event-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
