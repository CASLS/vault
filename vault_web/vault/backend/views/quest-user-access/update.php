<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\QuestUserAccess */

$this->title = 'Update Quest User Access: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Quest User Accesses', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="quest-user-access-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
