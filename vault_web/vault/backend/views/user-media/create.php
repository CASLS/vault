<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\UserMedia */

$this->title = 'Create User Media';
$this->params['breadcrumbs'][] = ['label' => 'User Media', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-media-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
