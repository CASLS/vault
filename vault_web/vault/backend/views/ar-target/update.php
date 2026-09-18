<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ArTarget */

$this->title = 'Update Ar Target: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Ar Targets', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ar-target-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<script type="text/javascript">

</script>