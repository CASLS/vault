<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\UserPref */

$this->title = 'Create User Pref';
$this->params['breadcrumbs'][] = ['label' => 'User Prefs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-pref-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
