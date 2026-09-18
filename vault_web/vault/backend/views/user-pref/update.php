<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\UserPref */

$this->title = 'Update User Pref: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'User Prefs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-pref-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
