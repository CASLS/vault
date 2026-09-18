<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ArTarget */

$this->title = 'Create Ar Target';
$this->params['breadcrumbs'][] = ['label' => 'Ar Targets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ar-target-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'quest' => NULL
    ]) ?>

</div>
