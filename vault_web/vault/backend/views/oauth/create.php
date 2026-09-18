<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Oauth */

$this->title = 'Create Oauth';
$this->params['breadcrumbs'][] = ['label' => 'Oauths', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="oauth-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
