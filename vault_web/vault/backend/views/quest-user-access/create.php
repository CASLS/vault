<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\QuestUserAccess */

$this->title = 'Create Quest User Access';
$this->params['breadcrumbs'][] = ['label' => 'Quest User Accesses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="quest-user-access-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
