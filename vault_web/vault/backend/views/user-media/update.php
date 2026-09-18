<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\UserMedia */

$this->title = 'Update User Media: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'User Media', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-media-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
