<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Oauth */

$this->title = 'Update Oauth: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Oauths', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="oauth-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
